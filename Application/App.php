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
use Table\Users;
use Symfony\Component\Console\Exception\RuntimeException;

class App extends Route
{
    /**
     * App constructor. If no uri is set than
     * the Route constructor will execute the
     * defaultRoute method defined below.
     * @param null $structure
     * @throws \Mustache_Exception_InvalidArgumentException
     * @throws \Carbon\Error\PublicAlert
     */
    public function __construct($structure = null)
    {
        $this->update();
        parent::__construct($structure);
    }


    public function update()
    {
        global $json, $user, $mustache;

        $mustache = function ($path) {
            global $json;
            static $mustache;
            if (empty($mustache)) {
                $mustache = new \Mustache_Engine();
            }
            if (!file_exists($path)) {
                print "<script>Carbon(() => $.fn.bootstrapAlert('Content Buffer Failed ($path), Does Not Exist!, 'danger'))</script>";
            }
            return $mustache->render(file_get_contents($path), $json);
        };


        $_SESSION['id'] = 'GoldTeam';

        if ($_SESSION['id'] ?? false) {
            $json['me'] = $GLOBALS['user'][$_SESSION['id']] ?? []; // TODO - remove default
            $json['signedIn'] = true;
            $json['nav-bar'] = '';
            $json['user-layout'] = 'class="wrapper" style="background: rgba(0, 0, 0, 0.7)"';

            $user[$_SESSION['id']]['user_type'] = 'Manager';

            switch ($user[$_SESSION['id']]['user_type'] ?? false) {
                case 'Manager':
                    $json['body-layout'] = 'hold-transition skin-blue layout-top-nav';

                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/Layout/Manager.hbs');

                    break;
                case 'Server':
                    $json['body-layout'] = 'skin-green fixed sidebar-mini sidebar-collapse';

                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/Layout/Server.hbs');

                    break;
                case 'Customer':
                default:
                    $json['body-layout'] = 'skin-green fixed sidebar-mini sidebar-collapse';

                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/Layout/Customer.hbs');
            }
        } else {
            $json['body-layout'] = 'stats-wrap';

            $json['user-layout'] = 'class="container" id="pjax-content"';
        }


        $json['SITE'] = SITE;
        $json['HTTP'] = HTTP;
        $json['HTTPS'] = HTTPS;
        $json['SOCKET'] = SOCKET;
        $json['AJAX'] = AJAX;
        $json['PJAX'] = PJAX;
        $json['SITE_TITLE'] = SITE_TITLE;
        $json['APP_VIEW'] = APP_VIEW;
        $json['TEMPLATE'] = TEMPLATE;
        $json['COMPOSER'] = COMPOSER;
        $json['X_PJAX_Version'] = &$_SESSION['X_PJAX_Version'];
        $json['FACEBOOK_APP_ID'] = FACEBOOK_APP_ID;

    }

    public function defaultRoute()  // Sockets will not execute this
    {
        View::$forceWrapper = true; // this will hard refresh the wrapper


        return $this->wrap()('User/login.php');  // don't change how wrap works, I know it looks funny

        /*
                if (!$_SESSION['id']):
                    return $this->wrap()('User/login.php');  // don't change how wrap works, I know it looks funny
                else:
                    return MVC('Golf', 'golf');
                endif;
        */
    }

    public
    function fullPage()
    {
        return catchErrors(function (string $file) {
            return include APP_VIEW . $file;
        });
    }

    public
    function wrap()
    {
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

            if (!\is_array($alert)) {
                $alert = array();
            }

            $json = array_merge($json, [
                'Errors' => $alert,
                'Event' => 'Controller->Model',   // This doesn't do anything.. Its just a mental note when I look at the json's in console (controller->model only)
                'Model' => $argv,
                'Mustache' => DS . $file,
                'Widget' => $selector
            ]);

            print PHP_EOL . json_encode($json) . PHP_EOL; // new line ensures it sends through the socket

            return true;
        };
    }
}
