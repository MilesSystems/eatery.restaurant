<?php

namespace App;

use Carbon\Error\PublicAlert;
use Carbon\View;
use Controller\User;

class Bootstrap extends App
{
    /**
     * Bootstrap constructor. Places basic variables
     * in our json response that will be needed by many pages.
     * @param null $structure
     * @throws \Carbon\Error\PublicAlert
     */
    public function __construct($structure = null)
    {
        global $json;

        $json = array();
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

        $this->userSettings();  // This is the current user state, if the user logs in or changes account types this will need to be refreshed

        parent::__construct($structure);
    }

    public function userSettings() {
        global $user, $json;

        // If the user is signed in we need to get the
        if ($_SESSION['id'] ?? false) {
            $json['me'] = $GLOBALS['user'][$_SESSION['id']];
            $json['signedIn'] = true;
            $json['nav-bar'] = '';
            $json['user-layout'] = 'class="wrapper" style="background: rgba(0, 0, 0, 0.7)"';

            //
            $mustache = function ($path) {      // This is our mustache template engine implemented in php, used for rendering user content
                global $json;
                static $mustache;
                if (empty($mustache)) {
                    $mustache = new \Mustache_Engine();
                }
                if (!file_exists($path)) {
                    print "<script>Carbon(() => $.fn.bootstrapAlert('Content Buffer Failed ($path), Does Not Exist!', 'danger'))</script>";
                }
                return $mustache->render(file_get_contents($path), $json);
            };

            /**
             * Nows a good time to note which layout is for the full
             * view/info page
             * hold-transition skin-blue layout-top-nav
             * skin-green fixed sidebar-mini sidebar-collapse
             *
             * respectively
             */

            switch ($user[$_SESSION['id']]['user_type'] ?? false) {
                default:
                case 'Customer':
                    $json['body-layout'] = 'skin-green fixed sidebar-mini sidebar-collapse';
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/Layout/Customer.hbs');
                    break;
                case 'Coach':
                    $json['body-layout'] = 'skin-green fixed sidebar-mini sidebar-collapse';
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'Layout/CoachLayout.hbs');
                    break;
                #default:
                 #   throw new PublicAlert('No user type found!!!!');
            }
        } else {
            $json['body-layout'] = 'stats-wrap';
            $json['user-layout'] = 'class="container" id="pjax-content"';
        }
    }


    public function defaultRoute()
    {
        // Sockets will not execute this
        View::$forceWrapper = true; // this will hard refresh the wrapper

        if (!$_SESSION['id']):
            return MVC('User', 'login');
        else:
            return $this->wrap()('GoldTeam/Manager/SalesReport.hbs');
        endif;
    }

    /**
     * @param null $uri
     * @return bool
     * @throws \Carbon\Error\PublicAlert
     */
    public function __invoke($uri = null)
    {
        if (null !== $uri) {
            $this->userSettings();          // Update the current user
            $this->changeURI($uri);
        }

        $this->structure($this->wrap());

        if ((string)$this->match('Developer/{view}', function ($view){

            print $view and die;

            $_SESSION['layout'] = $view; // TODO - remove before production
                $this->update();
                startApplication('login');
                return null;
            })) {
            return true;
        }

        ################################### Tables / Users
        if ((string)$this->match('Table/{number}/{page?}', function ($number, $page = null) {
            $validate = new \Carbon\Request();

            if ($validate->set($number)->int()) {
                $_SESSION['id'] = $number;           // Our wrapper will modify because this is set

                //$this->update();


                if ($validate->set($page)->word()) {
                    $page = strtolower($page);
                    if (file_exists(APP_ROOT . $view = (APP_VIEW . 'GoldTeam' . DS . $page . '.php'))) {
                        View::content($view);
                        return;
                    }
                    if (file_exists(APP_ROOT . $view = (APP_VIEW . 'GoldTeam' . DS . ucfirst($page) . '.php'))) {
                        View::content($view);
                        return;
                    }
                    if (file_exists(APP_ROOT . $view = (APP_VIEW . 'GoldTeam' . DS . 'Customer' . DS . $page . '.php'))) {
                        View::content($view);
                        return;
                    }
                    if (file_exists(APP_ROOT . $view = (APP_VIEW . 'GoldTeam' . DS . 'Customer' . DS . ucfirst($page) . '.php'))) {
                        View::content($view);
                        return;
                    }
                }
                View::content(APP_VIEW . 'GoldTeam/Home.php');

                return;
            }

            startApplication(true);
            exit(1);
        })) {
            return true;
        }

        if ((string)$this->match('Order', 'GoldTeam/Order.php')) {
            return true;
        }

        if ((string)$this->match('SalesReport', 'GoldTeam/Manager/SalesReport.hbs') ||
            (string)$this->match('Compensated', 'GoldTeam/Manager/Compensated.hbs')) {
            return true;
        }

        if ((string)$this->match('Schedule', 'GoldTeam/Manager/Calendar.hbs')) {
            return true;
        }

        #################################### Gold TEAM
        if ((string)$this->match('Home', 'GoldTeam/Home.php') ||
            (string)$this->match('About', 'GoldTeam/About.php') ||
            (string)$this->match('Tables', 'GoldTeam/Tables.php') ||
            (string)$this->match('Kitchen', 'GoldTeam/Kitchen.php') ||
            (string)$this->match('FAQ', 'GoldTeam/FAQ.php') ||
            (string)$this->match('Trial', 'GoldTeam/Trial.php') ||
            (string)$this->match('Features', 'GoldTeam/Features.php')
        ) {
            return true;
        }

        $this->structure($this->MVC());

        if ((string)$this->match('Contact', 'Messages', 'Mail')) {
            return true;
        }


################################### MVC
        // if (!$_SESSION['id']) {  // Signed out

        if ((string)$this->match('Login/*', 'User', 'login') ||
            (string)$this->match('oAuth/{service}/{request?}/*', 'User', 'oAuth') ||
            (string)$this->match('Register/*', 'User', 'Register') ||           // Register
            (string)$this->match('Recover/{user_email?}/{user_generated_string?}/', 'User', 'recover')) {     // Recover $userId
            return true;
        }

        // } else {
        // Event
        if (((AJAX && !PJAX) || SOCKET) && (
                (string)$this->match('Search/{search}/', 'Search', 'all') ||
                (string)$this->match('Messages/', 'Messages', 'navigation') ||
                (string)$this->match('Messages/{user_uri}/', 'Messages', 'chat') ||    // chat box widget
                (string)$this->structure($this->events())->match('Follow/{user_id}/', 'User', 'follow') ||
                (string)$this->match('Unfollow/{user_id}/', 'User', 'unfollow'))) {
            return true;         // Event
        }

        // $url->match('Notifications/*', 'notifications/notifications', ['widget' => '#NavNotifications']);

        // $url->match('tasks/*', 'tasks/tasks', ['widget' => '#NavTasks']);

        if (SOCKET) {
            return false;
        }                // Sockets only get json

        ################################### MVC
        $this->structure($this->MVC());
        if ((string)$this->match('Profile/{user_uri?}/', 'User', 'profile') ||   // Profile $user
            (string)$this->match('Messages/*', 'Messages', 'messages') ||
            (string)$this->match('Logout/*', function () {
                User::logout();
            })) {
            return true;          // Logout
        }

        // }

        return (string)$this->structure($this->MVC())->match('Activate/{email?}/{email_code?}/', 'User', 'activate') ||  // Activate $email $email_code
            (string)$this->structure($this->wrap())->match('404/*', 'Error/404error.php') ||
            (string)$this->match('500/*', 'Error/500error.php');
    }
}
