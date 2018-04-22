<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 2/25/18
 * Time: 3:20 AM
 */

namespace App;

use Carbon\Route;
use Carbon\View;

abstract class App extends Route
{
    /**
     * App constructor. If no uri is set than
     * the Route constructor will execute the
     * defaultRoute method defined below.
     * @return callable
     * @throws \Mustache_Exception_InvalidArgumentException
     * @throws \Carbon\Error\PublicAlert
     */

    public
    function fullPage() : callable
    {
        if (SOCKET || AJAX) {
            print 'Application Full Page Response Was reached';
            exit(1);
        }
        return catchErrors(function (string $file) {
            return include APP_VIEW . $file;
        });
    }

    public
    function wrap()
    {
        /**
         * @param string $file
         * @return bool
         */
        return function (string $file): bool {
            return View::content(APP_VIEW . $file);
        };
    }

    public
    function MVC()
    {
        return function (string $class, string $method, array &$argv = []) {
            return MVC($class, $method, $argv);         // So I can throw in ->structure($route->MVC())-> anywhere
        };
    }

    public
    function events($selector = '')
    {
        return function ($class, $method, $argv) use ($selector) {
            global $alert, $json;

            if (false === $argv = CM($class, $method, $argv)) {
                return false;
            }

            if (!file_exists(SERVER_ROOT . $file = (APP_VIEW . $class . DS . $method . '.hbs'))) {
                $alert = 'Mustache Template Not Found ' . $file;
            }

            $json = array_merge($json, [
                'Event' => 'Controller->Model',   // This doesn't do anything.. Its just a mental note when I look at the json's in console (controller->model only)
                'Model' => $argv,
                'Mustache' => DS . $file,
                'Widget' => $selector,
                'URI' => $_SERVER['REQUEST_URI']
            ]);

            #header('Content-Type: application/json'); // Send as JSON - not good for testing websocketd

            print json_encode($json) . PHP_EOL . PHP_EOL; // new line ensures it sends through the socket

            return true;
        };
    }
}
