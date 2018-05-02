<?php
/**
 * Copyright 2017 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Debugger;

use Google\Cloud\Core\Batch\BatchDaemonTrait;
use Google\Cloud\Core\Batch\BatchRunner;
use Google\Cloud\Core\Batch\BatchTrait;
use Google\Cloud\Core\ExponentialBackoff;
use Google\Cloud\Core\Exception\ServiceException;
use Google\Cloud\Core\SysvTrait;
use Google\Cloud\Debugger\BreakpointStorage\BreakpointStorageInterface;
use Google\Cloud\Debugger\BreakpointStorage\FileBreakpointStorage;
use Google\Cloud\Debugger\BreakpointStorage\SysvBreakpointStorage;
use Google\Cloud\Logging\LoggingClient;
use Psr\Log\LoggerInterface;

/**
 * This class is responsible for registering all debugger breakpoints and
 * logpoints for each request. It should be created as early as possible in
 * your application.
 *
 * Example:
 * ```
 * use Google\Cloud\Debugger\Agent;
 *
 * $agent = new Agent();
 * ```
 */
class Agent
{
    use BatchTrait;
    use BatchDaemonTrait;
    use SysvTrait;

    const DEFAULT_LOGPOINT_LOG_NAME = 'debugger_logpoints';
    const DEFAULT_APP_ENGINE_LOG_NAME = 'appengine.googleapis.com%2Frequest_log';
    const DEFAULT_MAX_DEPTH = 5;

    /**
     * @var string Unique identifier for the debuggee generated by the
     *      controller service.
     */
    private $debuggeeId;

    /**
     * @var array Associative array of breakpoints indexed by breakpoint id.
     */
    private $breakpointsById = [];

    /**
     * @var string Path to the root directory of the source code.
     */
    private $sourceRoot;

    /**
     * @var LoggerInterface A PSR-3 logger that handles logpoints.
     */
    private $logger;

    /**
     * @var int The maximum number of stackframes with captured variables.
     */
    private $maxDepth;

    /**
     * Create a new Debugger Agent, registers all breakpoints for collection
     * or execution, and registers a shutdown function for reporting results.
     *
     * @param array $options [optional] {
     *      Configuration options.
     *
     *      @type BreakpointStorageInterface $storage Breakpoint storage
     *            to fetch breakpoints from. **Defaults to** a new
     *            SysvBreakpointStorage instance.
     *      @type string $sourceRoot Path to the root of the source repository.
     *            **Defaults to** the directory of the calling file.
     *      @type LoggerInterface $logger PSR-3 compliant logger used to write
     *            logpoint records. **Defaults to** a new Stackdriver logger.
     *      @type array $daemonOptions Additional options to provide to the
     *            Daemon when registering.
     *      @type int $maxDepth Limits the number of stackframes with
     *            captured variables. To capture variables in all stackframes,
     *            set to PHP_INT_MAX. **Defaults to** 5.
     * }
     */
    public function __construct(array $options = [])
    {
        $options += [
            'daemonOptions' => [],
            'storage' => null,
            'sourceRoot' => null,
            'maxDepth' => self::DEFAULT_MAX_DEPTH
        ];
        $storage = $options['storage'] ?: $this->defaultStorage();
        $this->sourceRoot = $options['sourceRoot']
            ?: dirname(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0]['file']);
        $this->maxDepth = $options['maxDepth'];

        if ($this->shouldStartDaemon()) {
            $daemon = new Daemon($options['daemonOptions'] + [
                'sourceRoot' => $this->sourceRoot,
                'storage' => $storage,
                'register' => true
            ]);
        }

        list($this->debuggeeId, $breakpoints) = $storage->load();

        // skip starting the Agent unless the Daemon has already started and
        // registered the debuggee.
        if (empty($this->debuggeeId)) {
            return;
        }

        $this->setCommonBatchProperties($options + [
            'identifier' => 'stackdriver-debugger',
            'batchMethod' => 'insertBatch'
        ]);
        $this->logger = isset($options['logger'])
            ? $options['logger']
            : $this->defaultLogger();

        if (empty($breakpoints)) {
            return;
        }

        if (!extension_loaded('stackdriver_debugger')) {
            trigger_error('Breakpoints set but "stackdriver_debugger" extension not loaded', E_USER_WARNING);
            return;
        }

        foreach ($breakpoints as $breakpoint) {
            $this->breakpointsById[$breakpoint->id()] = $breakpoint;

            // Sometimes, when the debuggee is re-registered, empty
            // breakpoint(s) without a location may be present. In that case,
            // skip the breakpoint.
            $sourceLocation = $breakpoint->location();
            if (!$sourceLocation) {
                continue;
            }

            switch ($breakpoint->action()) {
                case Breakpoint::ACTION_CAPTURE:
                    stackdriver_debugger_add_snapshot(
                        $sourceLocation->path(),
                        $sourceLocation->line(),
                        [
                            'snapshotId'    => $breakpoint->id(),
                            'condition'     => $breakpoint->condition(),
                            'expressions'   => $breakpoint->expressions(),
                            'callback'      => [$this, 'handleSnapshot'],
                            'sourceRoot'    => $this->sourceRoot,
                            'maxDepth'      => $this->maxDepth
                        ]
                    );
                    break;
                case Breakpoint::ACTION_LOG:
                    stackdriver_debugger_add_logpoint(
                        $sourceLocation->path(),
                        $sourceLocation->line(),
                        $breakpoint->logLevel(),
                        $breakpoint->logMessageFormat(),
                        [
                            'snapshotId'    => $breakpoint->id(),
                            'condition'     => $breakpoint->condition(),
                            'expressions'   => $breakpoint->expressions(),
                            'callback'      => [$this, 'handleLogpoint'],
                            'sourceRoot'    => $this->sourceRoot
                        ]
                    );
                    break;
                default:
                    continue;
            }
        }
    }

    /**
     * Callback for reporting a snapshot.
     *
     * @access private
     * @param array $snapshot {
     *      Snapshot data
     *
     *      @type string $id The breakpoint id of the snapshot
     *      @type array $evaluatedExpressions The results of evaluating the
     *            snapshot's expressions
     *      @type array $stackframes List of captured stackframe data.
     * }
     */
    public function handleSnapshot(array $snapshot)
    {
        if (array_key_exists($snapshot['id'], $this->breakpointsById)) {
            $breakpoint = $this->breakpointsById[$snapshot['id']];
            $breakpoint->finalize();
            $breakpoint->addEvaluatedExpressions($snapshot['evaluatedExpressions']);
            foreach ($snapshot['stackframes'] as $index => $stackframe) {
                $breakpoint->addStackFrame($stackframe, [
                    'captureVariables' => $index < $this->maxDepth
                ]);
            }
            $this->batchRunner->submitItem($this->identifier, [$this->debuggeeId, $breakpoint]);
        }
    }

    /**
     * Callback for reporting a logpoint.
     *
     * @access private
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function handleLogpoint($level, $message, array $context = [])
    {
        $this->logger->log($level, "LOGPOINT: $message", $context);
    }

    /**
     * Callback for batch runner to report a breakpoint.
     *
     * @access private
     * @param array $breakpointsInfo
     */
    public function reportBreakpoints(array $breakpointsInfo)
    {
        $client = $this->defaultClient();
        foreach ($breakpointsInfo as $breakpointInfo) {
            list($debuggeeId, $breakpoint) = $breakpointInfo;
            $debuggee = $client->debuggee($debuggeeId);

            $backoff = new ExponentialBackoff();
            try {
                $backoff->execute(function () use ($breakpoint, $debuggee) {
                    $debuggee->updateBreakpoint($breakpoint);
                });
            } catch (ServiceException $e) {
                // Ignore this error for now
            }
        }
    }

    protected function getCallback()
    {
        return [$this, 'reportBreakpoints'];
    }

    private function defaultStorage()
    {
        return $this->isSysvIPCLoaded()
            ? new SysvBreakpointStorage()
            : new FileBreakpointStorage();
    }

    private function defaultClient()
    {
        return new DebuggerClient($this->getUnwrappedClientConfig());
    }

    private function defaultLogger()
    {
        $logName = isset($server['GAE_SERVICE'])
            ? self::DEFAULT_APP_ENGINE_LOG_NAME
            : self::DEFAULT_LOGPOINT_LOG_NAME;
        return LoggingClient::psrBatchLogger($logName);
    }

    private function shouldStartDaemon()
    {
        return $this->isDaemonRunning() && $this->isSysvIPCLoaded();
    }
}
