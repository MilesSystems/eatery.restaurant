<?php

use Carbon\Route;
use Carbon\View;

$url = new class extends Route
{

    /**
     *  constructor.
     * @param null $structure
     * @throws \Carbon\Error\PublicAlert the only
     * way this will throw an error is if you do
     * not define a url using sockets.
     */
    public function __construct($structure = null)
    {
        parent::__construct($structure);

        #if ($_SESSION['id']) {
        ##View::$wrapper = SERVER_ROOT . APP_VIEW . 'Layout/logged-in-layout.php';
        #}

    }


    public function defaultRoute()  // Sockets will not execute this
    {
        View::$forceWrapper = true; // this will hard refresh the wrapper

        if (!$_SESSION['id']):
            return $this->wrap()('GoldTeam/Home.php');  // don't change how wrap works, I know it looks funny
        else:
            return MVC('user', 'profile');
        endif;
    }

    public function fullPage()
    {
        return catchErrors(function (string $file) {
            return include APP_VIEW . $file;
        });
    }

    public function wrap()
    {
        return function (string $file): bool {
            return View::content(APP_VIEW . $file);
        };
    }

    public function MVC()
    {
        return function (string $class, string $method, array &$argv = []) {
            return MVC($class, $method, $argv);         // So I can throw in ->structure($route->MVC())-> anywhere
        };
    }

    public function events()
    {
        return function ($class, $method, $argv) {
            global $alert, $json;

            $argv = CM($class, $method, $argv);

            if (!is_array($alert)) {
                $alert = array();
            }

            $json = [
                'Errors' => $alert,
                'Event' => 'Controller->Model',        // This doesn't do anything.. Its just a mental note when I look at the json's in console (controller->model only)
                'Model' => $argv
            ];

            print PHP_EOL . json_encode($json) . PHP_EOL; // new line ensures it sends through the socket

            return true;
        };
    }
};

$url->structure($url->wrap());

################################### Tables / Users
if ((string)$url->match('Table/{number}/{page?}', function ($number, $page = null) {
    $validate = new \Carbon\Request();

    if ($validate->set($number)->int()) {
        $_SESSION['table'] = $number;           // Our wrapper will modify because this is set

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
            if (file_exists(APP_ROOT . $view = (APP_VIEW . 'GoldTeam' . DS. 'Customer' . DS . $page . '.php'))) {
                View::content($view);
                return;
            }
            if (file_exists(APP_ROOT . $view = (APP_VIEW . 'GoldTeam' . DS. 'Customer' . DS . ucfirst($page) . '.php'))) {
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


#################################### Gold TEAM
if ((string)$url->match('Home', 'GoldTeam/Home.php') ||
    (string)$url->match('About', 'GoldTeam/About.php') ||
    (string)$url->match('Tables', 'GoldTeam/Tables.php') ||
    (string)$url->match('Kitchen', 'GoldTeam/Kitchen.php') ||
    (string)$url->match('FAQ', 'GoldTeam/FAQ.php') ||
    (string)$url->match('Trial', 'GoldTeam/Trial.php') ||
    (string)$url->match('Features', 'GoldTeam/Features.php')
) {
    return true;
}

$url->structure($url->MVC());

if ((string)$url->match('Contact', 'Messages', 'Mail')) {
    return true;
}


################################### MVC
if (!$_SESSION['id']) {  // Signed out

    if ((string)$url->match('Login/*', 'User', 'login') ||
        (string)$url->match('Google/{request?}/*', 'User', 'google') ||
        (string)$url->match('Facebook/{request?}/*', 'User', 'facebook') ||
        (string)$url->match('Register/*', 'User', 'Register') ||           // Register
        (string)$url->match('Recover/{user_email?}/{user_generated_string?}/', 'User', 'recover')) {     // Recover $userId
        return true;
    }

} else {
    // Event
    if (((AJAX && !PJAX) || SOCKET) && (
            (string)$url->match('Search/{search}/', 'Search', 'all') ||
            (string)$url->match('Messages/', 'Messages', 'navigation') ||
            (string)$url->match('Messages/{user_uri}/', 'Messages', 'chat') ||    // chat box widget
            (string)$url->structure($url->events())->match('Follow/{user_id}/', 'User', 'follow') ||
            (string)$url->match('Unfollow/{user_id}/', 'User', 'unfollow'))) {
        return true;         // Event
    }

    // $url->match('Notifications/*', 'notifications/notifications', ['widget' => '#NavNotifications']);

    // $url->match('tasks/*', 'tasks/tasks', ['widget' => '#NavTasks']);

    if (SOCKET) {
        return false;
    }                // Sockets only get json

    ################################### MVC
    $url->structure($url->MVC());
    if ((string)$url->match('Profile/{user_uri?}/', 'User', 'profile') ||   // Profile $user
        (string)$url->match('Messages/*', 'Messages', 'messages') ||
        (string)$url->match('Logout/*', function () {
            Controller\User::logout();
        })) {
        return true;          // Logout
    }

}

return (string)$url->structure($url->MVC())->match('Activate/{email?}/{email_code?}/', 'User', 'activate') ||  // Activate $email $email_code
    (string)$url->structure($url->wrap())->match('404/*', 'Error/404error.php') ||
    (string)$url->match('500/*', 'Error/500error.php');

