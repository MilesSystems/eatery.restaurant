<?php

namespace App;

use Controller\User;
use Carbon\View;

class Bootstrap extends App
{
    /**
     * @param null $uri
     * @return bool
     * @throws \Carbon\Error\PublicAlert
     */
    public function __invoke($uri = null)
    {
        if (null !== $uri) {
            $this->changeURI($uri);
        }

        #$this->structure($this->MVC());

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


        if ((string)$this->match('SalesReport', 'GoldTeam/SalesReport.php')) {
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
            (string)$this->match('Google/{request?}/*', 'User', 'google') ||
            (string)$this->match('Facebook/{request?}/*', 'User', 'facebook') ||
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
