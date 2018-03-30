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

    /**
     * @throws PublicAlert
     */
    public function userSettings()
    {
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

            $json['body-layout'] = 'skin-green fixed sidebar-mini sidebar-collapse';

            switch ($user[$_SESSION['id']]['user_type'] ?? false) {
                case 'Customer':
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/Customer.hbs');
                    break;
                case 'Waiter':
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/Waiter.hbs');
                    break;
                case 'Kitchen':
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/Kitchen.hbs');
                    break;
                case 'Manager':
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/Manager.hbs');
                    break;
                default:
                    $json['body-layout'] = 'hold-transition skin-blue layout-top-nav';                // The general elements are top nav
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'GoldTeam/General.hbs');
                    throw new PublicAlert('No user type found!!!!');
            }
        } else {
            $json['body-layout'] = 'stats-wrap';
            $json['user-layout'] = 'class="container" id="pjax-content"';
        }
    }


    /** This will be executed if the uri is null, or
     *  if startApplication does not match the uri
     * @return mixed
     */
    public function defaultRoute()
    {

        // Sockets will not execute this
        View::$forceWrapper = true; // this will hard refresh the wrapper

        if (!$_SESSION['id']):
            return MVC('User', 'login');
        else:
            return MVC('User', 'profile');
        endif;
    }

    /** we dont use this return value for anything
     * @param null $uri
     * @return bool
     * @throws \Carbon\Error\PublicAlert
     */
    public function startApplication($uri = null): ? bool
    {

        if (null !== $uri) {
            $this->userSettings();          // Update the current user
            $this->changeURI($uri);
        }

        ################################### MVC
        $this->structure($this->MVC());

        if (!$_SESSION['id']) {
            return $this->match('Login/*', 'User', 'login')() ||
                $this->match('Contact', 'Messages', 'Mail')() ||
                $this->match('oAuth/{service}/{request?}/*', 'User', 'oAuth')() ||
                $this->match('Register/*', 'User', 'Register')() ||           // Register
                $this->match('Recover/{user_email?}/{user_generated_string?}/', 'User', 'recover')();    // Recover $userId

        }
        ################################### TODO - Delete developer options
        $this->match('Developer/{AccountType}', 'User', 'accountType');


        ################################### Static / Logged IN
        global $user;

        switch ($user[$_SESSION['id']]['user_type'] ?? false) {
            case 'Manager':

                if ($this->match('SalesReport', 'Manager', 'SalesReport')() ||
                    $this->match('EditMenu','Manager', 'EditMenu')() ||
                    $this->match('Schedule', 'Schedule', 'Schedule')() ||
                    $this->match('Employees', 'Manager', 'Employees')() ||
                    $this->match('Costumers', 'Manager', 'Costumers')() ||
                    $this->match('Compensated', 'Manager', 'Compensated')() ||
                    $this->match('Menu', 'Manager', 'Menu')()) {
                    return true;
                }

            case 'Waiter':
                if ($this->match('Tables/{tables?}/*', '', '')()){
                    return true;
                }

            case 'Kitchen':
                if ($this->match('Kitchen','Kitchen', 'Orders')()){
                    return true;
                }

            case 'Customer' :
                if ($this->match('Games/{game?}/', 'Customer', 'games')()) {
                    return true;
                }

            default:

        }
        ################################### MVC
        $this->structure($this->MVC());

        if ($this->match('Profile/{user_uri?}/', 'User', 'profile')() ||   // Profile $user
            $this->match('Messages/*', 'Messages', 'messages')() ||
            $this->match('Logout/*', function () { User::logout(); })()) {
            return true;          // Logout
        }

        #################################### Events
        if (((AJAX && !PJAX) || SOCKET) && (
                $this->match('Search/{search}/', 'Search', 'all')() ||
                $this->match('Messages/', 'Messages', 'navigation')() ||
                $this->match('Messages/{user_uri}/', 'Messages', 'chat')() ||    // chat box widget
                $this->structure($this->events())->match('Follow/{user_id}/', 'User', 'follow')() ||
                $this->match('Unfollow/{user_id}/', 'User', 'unfollow')())) {
            return true;         // Event
        }

        // $url->match('Notifications/*', 'notifications/notifications', ['widget' => '#NavNotifications']);
        // $url->match('tasks/*', 'tasks/tasks', ['widget' => '#NavTasks']);

        #################################### Gold TEAM Static
        $this->structure($this->wrap());    // TODO - cross over
        if ($this->match('Home', 'GoldTeam/Static/Home.php')() ||
            $this->match('About', 'GoldTeam/Static/About.php')() ||
            $this->match('FAQ', 'GoldTeam/Static/FAQ.php')() ||
            $this->match('Trial', 'GoldTeam/Static/Trial.php')() ||
            $this->match('Features', 'GoldTeam/Static/Features.php')()) {
            return true;
        }

        return $this->structure($this->MVC())->match('Activate/{email?}/{email_code?}/', 'User', 'activate')() ||  // Activate $email $email_code
            $this->structure($this->wrap())->match('404/*', 'Error/404error.php')() ||
            $this->match('500/*', 'Error/500error.php')();
    }

}
