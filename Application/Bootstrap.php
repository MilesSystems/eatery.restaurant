<?php

namespace App;

use Carbon\Error\PublicAlert;
use Carbon\View;
use Controller\User;
use Model\Helpers\GlobalMap;
use Table\Cart;
use Table\Items;
use Table\Category;
use Table\Messages;
use Table\Notifications;

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
        global $json, $alert;

        $_SESSION['id'] = $_SESSION['id'] ?? false;         // it is done.

        $json = [
            'ALERT' => &$alert,
            'APP_VIEW' => APP_VIEW,
            'AJAX' => AJAX,
            'COMPOSER' => COMPOSER,
            'FACEBOOK_APP_ID' => FACEBOOK_APP_ID,
            'HTTP' => HTTP,
            'HTTPS' => HTTPS,
            'PJAX' => PJAX,
            'SITE' => SITE,
            'SITE_TITLE' => SITE_TITLE,
            'SOCKET' => SOCKET,
            'TEMPLATE' => TEMPLATE,
            'X_PJAX_Version' => &$_SESSION['X_PJAX_Version']
        ];

        $this->userSettings();  // This is the current user state, if the user logs in or changes account types this will need to be refreshed

        parent::__construct($structure);
    }

    /**
     * @throws PublicAlert
     */
    public function userSettings()
    {
        global $user, $json;

        //
        $mustache = function ($path) {      // This is our mustache template engine implemented in php, used for rendering user content
            global $json;
            static $mustache;

            if (SOCKET) {
                return 'SOCKET MUSTACHE (BOOTSTRAP -> userSettings)';
            }

            if (empty($mustache)) {
                $mustache = new \Mustache_Engine();
            }
            if (!file_exists($path)) {
                print "<script>Carbon(() => $.fn.bootstrapAlert('Content Buffer Failed ($path), Does Not Exist!', 'danger'))</script>";
            }
            return $mustache->render(file_get_contents($path), $json);
        };

        $json['messages'] = $json['notifications'] = [];

        Notifications::All($json['notifications'], session_id());

        Messages::All($json['messages'], session_id());

        $json['newNotifications'] = (!empty($json['notifications']) ? \count($json['notifications']) : null);

        // If the user is signed in we need to get the
        if (($_SESSION['id'] ?? false) || ($_SESSION['table_number'] ?? false)) {

            $json['me'] = $GLOBALS['user'][$_SESSION['id']] ?? false;
            $json['signedIn'] = true;
            $json['nav-bar'] = '';
            $json['user-layout'] = 'class="wrapper" style="background: rgba(0, 0, 0, 0.7)"';
            $json['table_number'] = $_SESSION['table_number'] ?? false;


            /**
             * Nows a good time to note which layout is for the full
             * view/info page
             * hold-transition skin-blue layout-top-nav
             * skin-green fixed sidebar-mini sidebar-collapse
             *
             * respectively
             */

            switch ($user[$_SESSION['id']]['user_type'] ?? $_SESSION['table_number'] ?? false) {
                default:
                    $json['body-layout'] = 'hold-transition skin-blue layout-top-nav';                // The general elements are top nav
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'goldTeam/General.hbs');
                    // throw new PublicAlert('No user type found!!!!');
                    break;
                case 'Waiter':
                    $json['body-layout'] = 'skin-purple fixed sidebar-mini sidebar-collapse';
                    if (!AJAX && !SOCKET) {
                        $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'goldTeam/Waiter.hbs');
                    }
                    break;
                case 'Kitchen':
                    $json['body-layout'] = 'skin-red fixed sidebar-mini sidebar-collapse';
                    if (!AJAX && !SOCKET) {
                        $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'goldTeam/Kitchen.hbs');
                    }
                    break;
                case 'Manager':
                    $json['body-layout'] = 'skin-black fixed sidebar-mini sidebar-collapse';

                    // TODO - is this a shitty fix for our problem?
                    if (!AJAX && !SOCKET) {
                        $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'goldTeam/Manager.hbs');
                    }
                    break;
                case 0:
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                case 11:
                case 12:
                case 13:
                case 14:
                case 15:
                case 16:
                case 'Customer':

                    $json['category'] = [];

                    Category::All($json['category'], '');
                    foreach ($json['category'] as $key => $value) {
                        $json['category'][$key]['item'] = array();
                        Items::All($json['category'][$key]['item'], $json['category'][$key]['category_id']);
                    }

                    $json['items'] = [];

                    Cart::Get($json['items'], session_id(), []);

                    $json['cartNotifications'] = (!empty($json['items']) ? \count($json['items']) : false);

                    if ($json['cartNotifications']) {
                        foreach ($json['items'] as $key => $value) {
                            Items::Get($json['items'][$key], $value['cart_item'], []);
                        }
                    }

                    $json['body-layout'] = 'skin-green fixed sidebar-mini sidebar-collapse';
                    $json['header'] = $mustache(APP_ROOT . APP_VIEW . 'goldTeam/Customer.hbs');
                    break;

            }
        } else {
            $json['body-layout'] = 'stats-wrap';
            $json['user-layout'] = 'class="container" id="pjax-content"';       // aka login menu // register
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

        if (!($_SESSION['id']) && !($_SESSION['table_number'] ?? false)):
            return MVC('User', 'login');
        else:
            return $this->wrap()('goldTeam/Static/Home.hbs');
        endif;
    }

    /** we dont use this return value for anything
     * @param null $uri
     * @return bool
     * @throws \Carbon\Error\PublicAlert
     */
    public function startApplication($uri = null): ? bool
    {
        static $count;

        if (empty($count)) {
            $count = 0;
        }

        $count++;

        if (null !== $uri) {
            $this->userSettings();          // Update the current user
            $this->changeURI($uri);         // This is different from the uri change done in the startApplication fn
        } else {
            if (empty($this->uri[0])) {
                if (SOCKET) {
                    throw new PublicAlert('URI MUST BE SET IN SOCKET REQUESTS');
                }
                $this->matched = true;
                return $this->defaultRoute();
            }
        }

        ################################### TODO - Delete developer options
        $this->structure($this->MVC())->match('Developer/{AccountType}', 'User', 'accountType');

        #################################### Gold TEAM Static
        $this->structure($this->wrap());    // TODO - cross over
        if ($this->match('Home', 'goldTeam/Static/Home.php')() ||
            $this->match('About', 'goldTeam/Static/About.php')() ||
            $this->match('FAQ', 'goldTeam/Static/FAQ.php')() ||
            $this->match('Trial', 'goldTeam/Static/Trial.php')() ||
            $this->match('Features', 'goldTeam/Static/Features.php')()) {
            return true;
        }

        ################################### MVC
        $this->structure($this->MVC());

        if ($this->match('clearnotifications', 'User', 'clearnotifications')()) {
            return true;
        }


        if (!$_SESSION['id']) {
            if ($_SESSION['table_number'] ?? false) {


                ############################# we need to make sure the customers view is available when logged off

                if ($this->structure($this->events('.orderCart'))->match('cartNotifications', 'Customer', 'cart')() ||
                    $this->structure($this->events('#NavNotifications'))->match('cartNotifications', 'Customer', 'refill')()) {
                    return true;
                }

                $this->structure($this->MVC());
                if ($this->match('PlaceOrder', 'Customer', 'PlaceOrder')() ||
                    $this->match('ViewCheck', 'Customer', 'ViewCheck')() ||
                    $this->match('ClearTable', 'User', 'ClearTable')() ||
                    $this->structure($this->wrap())->match('CrappyBird-master/', 'CrappyBird-master/index.html')() ||
                    $this->structure($this->fullPage())->match('javaScript/', 'javaScript/index.html')() ||
                    $this->structure($this->MVC())->match('MenuItems/{game?}/', 'Customer', 'games')() ||
                    $this->match('Item/{itemId}', 'Customer', 'Item')()) {
                    return true;
                }
            }



            return $this->match('Login/*', 'User', 'login')() ||
                $this->match('Tables/{number?}/*', 'User', 'Tables')() ||
                $this->match('Contact', 'Messages', 'Mail')() ||
                $this->match('oAuth/{service}/{request?}/*', 'User', 'oAuth')() ||
                $this->match('Register/*', 'User', 'Register')() ||           // Register
                $this->match('Recover/{user_email?}/{user_generated_string?}/', 'User', 'recover')();    // Recover $userId

        }


        ###################################  Logged IN
        global $user;

        if ($this->structure($this->events('#NavNotifications'))->match('notifications', 'User', 'notifications')()) {
            return true;
        }

        switch ($user[$_SESSION['id']]['user_type'] ?? false) {
            case 'Manager':


                if ($this->structure($this->events('accordion'))->match('accordion', 'Manager', 'accordion')()) {
                    return true;
                }

                $this->structure($this->MVC());

                if ($this->match('Messages/*', 'Manager', 'messages')() ||
                    $this->match('SalesReport', 'Manager', 'SalesReport')() ||
                    $this->match('changeType/{user_id}/{user_type}', 'Manager', 'changeType')() ||
                    $this->match('hideCategory/{category_id}', 'Manager', 'hideCategory')() ||
                    $this->match('EditMenu', 'Manager', 'EditMenu')() ||
                    $this->match('Schedule', 'Schedule', 'Schedule')() ||
                    $this->match('Employees', 'Manager', 'Employees')() ||
                    $this->match('Costumers', 'Manager', 'customers')() ||
                    $this->match('Compensated', 'Manager', 'Compensated')() ||
                    $this->match('Menu/{forum?}/', 'Manager', 'Menu')()) {
                    return true;
                }
            case 'Waiter':
                if ($this->structure($this->MVC())->match('ViewTables/{table_id?}', 'Waiter', 'ViewTables')()) {
                    return true;
                }

            case 'Kitchen':
                if ($this->structure($this->MVC())->match('Kitchen', 'Kitchen', 'orders')() ||
                    $this->match('StartOrder/{orderId}', 'Kitchen', 'StartOrder')() ||
                    $this->match('CompleteOrder/{orderId}', 'Kitchen', 'CompleteOrder')()) {
                    return true;
                }

            case 'Customer' :

                // $_SESSION['table_number'] = 1;
                if (!array_key_exists('table_number',$_SESSION) && ($user[$_SESSION['id']]['user_type'] === 'Customer')) {
                    if ($this->structure($this->MVC())->match('Tables/{tableNumber}', 'Customer', 'setTable')()) {
                        return true;
                    }
                    $argv = ['0'];
                    MVC('User', 'Tables', $argv);
                    return true;
                }


                if ($this->structure($this->events('.orderCart'))->match('cartNotifications', 'Customer', 'cart')() ||
                    $this->structure($this->events('#NavNotifications'))->match('requestHelp', 'Customer', 'help')()||
                    $this->structure($this->events('#NavNotifications'))->match('requestRefill', 'Customer', 'refill')()
                ) {
                    return true;
                }

                $this->structure($this->MVC());
                if ($this->match('completeOrder/{order_id}/', 'Customer','completeOrder')() ||
                    $this->match('PlaceOrder', 'Customer', 'PlaceOrder')() ||
                    $this->match('ViewCheck', 'Customer', 'ViewCheck')() ||
                    $this->structure($this->wrap())->match('CrappyBird-master/', 'CrappyBird-master/index.html')() ||
                    $this->structure($this->wrap())->match('javaScript/', 'javaScript/index.html')() ||
                    $this->structure($this->MVC())->match('MenuItems/{game?}/', 'Customer', 'games')() ||
                    $this->match('Item/{itemId}', 'Customer', 'Item')()) {
                    return true;
                }


            default:

        }
        ################################### MVC
        $this->structure($this->MVC());

        if ($this->match('Profile/{user_uri?}/', 'User', 'profile')() ||   // Profile $user
            $this->match('Messages/*', 'Messages', 'messages')() ||
            $this->match('Logout/*', function () {
                User::logout();
            })()) {
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


        return $this->structure($this->MVC())->match('Activate/{email?}/{email_code?}/', 'User', 'activate')() ||  // Activate $email $email_code
            $this->structure($this->wrap())->match('404/*', 'error/404error.php')() ||
            $this->match('500/*', 'error/500error.php')();
    }

}
