<?php

namespace Model;

use Carbon\Helpers\Serialized;
use Model\Helpers\GlobalMap;
use Table\Users;
use Table\Followers;
use Table\Messages;
use Carbon\Error\PublicAlert;
use Carbon\Helpers\Bcrypt;
use Carbon\Request;

/**
 * Class User
 * @package Model
 */
class User extends GlobalMap
{
    /**
     * User constructor.
     * @param string|null $id
     * @throws \Carbon\Error\PublicAlert
     */
    public function __construct(string $id = null)
    {
        // Used to get team member
        parent::__construct();

        if (!\is_array($this->user)) {
            $this->user = [];               // TODO - I used to throw an exception
        }

        if ($_SESSION['id'] === $id) {
            return; // We've already gotten current user data
        }
        if ($_SESSION['id'] && $id !== null) {
            Users::All($this->user[$id], $id);
            Followers::All($this->user[$id], $id);
            Messages::All($this->user[$id], $id);
        }
    }

    /**
     * @param $username
     * @param $password
     * @param $rememberMe
     * @throws PublicAlert
     */
    public function login($username, $password, $rememberMe)
    {
        if (!Users::user_exists($username)) {
            throw new PublicAlert('Sorry, this Username and Password combination doesn\'t match out records.', 'warning');
        }

        // We get this for the cookies
        $sql = 'SELECT user_password, user_first_name, user_last_name, user_profile_pic, user_id FROM carbon_users WHERE user_username = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $data = $stmt->fetch();


        // using the verify method to compare the password with the stored hashed password.
        if (Bcrypt::verify($password, $data['user_password']) === true) {
            /*
             * TODO - the mail function doesn't work in google vm
            if (!Users::email_confirmed($username)) {
                throw new PublicAlert('Sorry, you need to activate your account. Please check your email!', 'warning');
            }
            */
            $_SESSION['id'] = $data['user_id'];    // returning the user's id.
        } else {
            throw new PublicAlert ('Sorry, the username and password combination you have entered is invalid.', 'warning');
        }

        if ($rememberMe) {
            Request::setCookie('UserName', $username);
            Request::setCookie('FullName', $data['user_first_name'] . ' ' . $data['user_last_name']);
            Request::setCookie('UserImage', $data['user_profile_pic']);
        } else {
            (new Request)->clearCookies();
        }

        startApplication(true);
        exit(1);
    }

    /**
     * @param $request
     * @return string
     * @throws \Carbon\Error\PublicAlert
     */
    public function facebook($request) : ?string
    {
        global $facebook;

        Request::changeURI('Facebook/');    // Facebook sends there data in the URL, this will erase it

        if (empty($facebook)) {
            startApplication('login');     // This will restart the route to the login page
            exit(1);
        }

        $sql = 'SELECT user_id, user_facebook_id FROM carbon_users WHERE user_email = ? OR user_facebook_id =?';
        $sql = self::fetch($sql, $facebook['email'], $facebook['id']);

        $C6_id = $sql['user_id'] ?? false;
        $fb_id = $sql['user_facebook_id'] ?? false;

        if (!$C6_id && !$fb_id): // This person has needs a new account
            if ($request === 'SignUp'):          // They asked for a new account
                Users::Post([
                    'username' => $facebook['username'],
                    'password' => $facebook['password'],
                    'facebook_id' => $facebook['id'],
                    'profile_pic' => $facebook['picture']['url'] ?? '',
                    'cover_photo' => $facebook['cover']['source'] ?? '',
                    'email' => $facebook['email'],
                    'type' => '',
                    'first_name' => $facebook['first_name'],
                    'last_name' => $facebook['last_name'],
                    'gender' => $facebook['gender']
                ]);
            else:       // They wanted to sign in? but you've never logged in this way...

                if (($_SESSION['facebook'] ?? false) && \is_array($_SESSION['facebook'])) {
                    $facebook = $_SESSION['facebook'];
                } else {   // were trying to sign in when we should be signing up
                    $_SESSION['facebook'] = $facebook;
                }
                $request = 'SignUp';        // Lets ask them for more info
                return $request;
            endif;
        elseif ($C6_id && !$fb_id):         // We have some matching info with this account

            if ($request === 'SignIn'):     // And they want to link there account

                $sql = 'UPDATE carbon_user SET user_facebook_id = ? WHERE user_id = ?';     // UPDATE user
                $this->db->prepare($sql)->execute([$facebook['id'], $_SESSION['id']]);
                $_SESSION['id'] = $C6_id;

            else:  //

                if ($_SESSION['facebook'] ?? false) {
                    $facebook = $_SESSION['facebook'];
                } else {
                    $_SESSION['facebook'] = $facebook;  // were trying to signup when we need to signin
                }
                $request = 'SignIn';
                return $request;
            endif;
        else:
            $_SESSION['id'] = $C6_id;
        endif;

        $_SESSION['facebook'] = $facebook = null;
        startApplication(true);                 // If the session id is set they will be logged in..
        exit(1);
    }

    /**
     * @param $user_id
     * @return bool
     * @throws PublicAlert
     */
    public function follow($user_id): bool
    {
        if (!$out = Users::user_exists($user_id)) {
            throw new PublicAlert("That user does not exist $user_id >> $out");
        }
        return Followers::Post([$user_id]);
    }

    /**
     * @param $user_id
     * @return bool
     * @throws PublicAlert
     */
    public function unfollow($user_id) : bool
    {
        if (!Users::user_exists($user_id)) {
            throw new PublicAlert('That user does not exist?!');
        }
        return Followers::Delete($this->user[$_SESSION['id']], $user_id);

    }

    /**
     * @param $request
     */
    public function google($request)
    {

    }

    /**
     * @throws PublicAlert
     */
    public function register()
    {
        global $username, $password, $email, $firstName, $lastName, $gender;

        if (Users::user_exists($username)) {
            throw new PublicAlert ('That username already exists', 'warning');
        }

        if (Users::email_exists($email)) {
            throw new PublicAlert ('That email already exists.', 'warning');
        }

        // Tables self validate and throw public errors
        Users::Post([
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => $gender
        ]);

        PublicAlert::success('Welcome to '. SITE_TITLE.'! Please check your email to finish your registration.');

        startApplication(true);
        exit(1);
    }

    /**
     * @param $email
     * @param $email_code
     * @return bool
     * @throws PublicAlert
     */
    public function activate($email, $email_code): bool
    {
        if (!Users::email_exists($email)) {
            throw new PublicAlert('Please make sure the Url you have entered is correct.', 'danger');
        }

        $stmt = $this->db->prepare('SELECT COUNT(user_id) FROM StatsCoach.user WHERE user_email = ? AND user_email_code = ?');
        $stmt->execute([$email, $email_code]);

        if ($stmt->fetch() === 0) {
            PublicAlert::warning('Sorry, you may be using an old activation code.');
            return startApplication(true);
        }

        if (!$this->db->prepare('UPDATE carbon_users SET user_email_confirmed = 1 WHERE user_email = ?')->execute(array($email))) {
            throw new PublicAlert('The code provided appears to be invalid.', 'danger');
        }


        $stmt = $this->db->prepare('SELECT user_id FROM carbon_users WHERE user_email = ?');
        $stmt->execute([$email]);
        $_SESSION['id'] = $stmt->fetchColumn();
        PublicAlert::success('We successfully activated your account.');
        startApplication(true); // there is not an activate template file
        exit(1);
    }

    /**
     * @param $email
     * @param $generated_string
     * @throws PublicAlert
     */
    public function recover($email, $generated_string)
    {
        $alert = function () {
            throw new PublicAlert('An account could not be found with the email provided.', 'warning');
        };

        if (!Users::email_exists($email)) {
            $alert();
        }

        $generated = Bcrypt::genRandomHex(20);

        if (empty($generated_string)) {
            $sql = 'SELECT user_first_name  FROM user WHERE user_email = ?';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            $user_first_name = $stmt->fetchColumn();

            $stmt = $this->db->prepare('UPDATE user SET user_generated_string = ? WHERE user_email = ?');
            if (!$stmt->execute([$generated, $email])) {
                throw new PublicAlert('Sorry, we failed to recover your account.', 'danger');
            }
            $subject = 'Your' . SITE_TITLE . ' password';
            $headers = 'From: Support@Stats.Coach' . "\r\n" .
                'Reply-To: Support@Stats.Coach' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            $message = 'Hello ' . $user_first_name . ",
            \r\nPlease click the link below:\r\n\r\n" . SITE . 'Recover/' . base64_encode($email) . "/" . base64_encode($generated) . "/\r\n\r\n 
            We will generate a new password for you and send it back to your email.\r\n\r\n--" . SITE_TITLE;

            mail($email, $subject, $message, $headers);

            PublicAlert::info('If an account is found, an email will be sent to the account provided.');

        } else {
            $sql = 'SELECT user_id, user_first_name FROM carbon_users WHERE user_email = ? AND user_generated_string = ?';
            $stmt = $this->db->prepare($sql);
            if (!$stmt->execute([$email, $generated_string])) {
                $alert();
            }
            if (empty($user = $stmt->fetch())) {
                $alert();
            }

            $this->change_password($user['user_id'], $generated);
            $stmt = $this->db->prepare('UPDATE carbon_users SET user_generated_string = 0 AND user_email_code = 0 AND user_email_confirmed = 1 WHERE user_id = ?');
            $stmt->execute([$user['user_id']]);

            $subject = 'Your' . SITE_TITLE . ' password';
            $headers = 'From: Support@Stats.Coach' . "\r\n" .
                'Reply-To: Support@Stats.Coach' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            $message = "Hello {$user['user_first_name']} ,\n\nYour your new password is: " . $generated .
                "\n\nPlease change your password once you have logged in using this password.\n\n-- " . SITE_TITLE;

            mail($email, $subject, $message, $headers);
            PublicAlert::success('Your password has been successfully reset.');
        }
        startApplication('login');

    }

    /**
     * @param bool $user_uri
     * @return void
     * @throws PublicAlert
     */
    public function profile($user_uri = false) : void
    {
        if ($user_uri === 'DeleteAccount') {
            Users::Delete($this->user[$_SESSION['id']], $_SESSION['id']);
            Serialized::clear();
            startApplication(true);
            exit(1);
        }

        if ($user_uri) {
            global $user_id;
            $user_id = Users::user_id_from_uri($user_uri);
            if (!empty($user_id) && $user_id !== $_SESSION['id']) {
                new User($user_id);
                return;
            }
        }

        Users::All($this->user[$_SESSION['id']], $_SESSION['id']);

        if (empty($_POST)) {
            return;
        }

        // we can assume post is active then
        global $first, $last, $email, $gender, $dob, $password, $profile_pic, $about_me;

        // $this->user === global $user
        $my = $this->user[$_SESSION['id']];

        $sql = 'UPDATE carbon_users SET user_profile_pic = :user_profile_pic, user_first_name = :user_first_name, user_last_name = :user_last_name, user_birthday = :user_birthday, user_email = :user_email, user_email_confirmed = :user_email_confirmed,  user_gender = :user_gender, user_about_me = :user_about_me WHERE user_id = :user_id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_profile_pic', $profile_pic ?: $my['user_profile_pic']);
        $stmt->bindValue(':user_first_name', $first ?: $my['user_first_name']);
        $stmt->bindValue(':user_last_name', $last ?: $my['user_last_name']);
        $stmt->bindValue(':user_birthday', $dob ?: $my['user_birthday']);
        $stmt->bindValue(':user_gender', $gender ?: $my['user_gender']);
        $stmt->bindValue(':user_email', $email ?: $my['user_email']);
        $stmt->bindValue(':user_email_confirmed', $email ? 0 : $my['user_email_confirmed']);
        $stmt->bindValue(':user_about_me', $about_me ?: $my['user_about_me']);
        $stmt->bindValue(':user_id', $_SESSION['id']);

        if (!$stmt->execute()) {
            throw new PublicAlert('Sorry, we could not process your information at this time.', 'warning');
        }

        if (!empty($password)) {
            Users::change_password($password);
        }

        // Remove old picture
        if (!empty($profile_pic) && !empty($my['user_profile_pic']) && $profile_pic !== $my['user_profile_pic']) {
            unlink(SERVER_ROOT . $my['user_profile_pic']);
        }

        // Send new activation code
        if (!empty($email) && $email !== $my['user_email']) {
            $subject = 'Please confirm your email';
            $headers = 'From: ' . SYSTEM_EMAIL . "\r\n" .
                'Reply-To: ' . REPLY_EMAIL . "\r\n" .
                'X-Mailer: PHP/' . PHP_VERSION;

            $message = 'Hello ' . ($first ?: $my['user_first_name']) . ",
            \r\n Please visit the link below so we can activate your account:\r\n\r\n
             https://www.Stats.Coach/Activate/" . base64_encode($email) . '/' . base64_encode($my['user_email_code']) . "/ \r\n\r\n Happy Golfing \r\n--" . SITE;


            if (!mail($email ?: $my['user_email'], $subject, $message, $headers)) {
                throw new PublicAlert('Our email system failed.');
            }
            PublicAlert::success('Please check your email to activate your account!');
        } else {
            PublicAlert::success('Your account has been updated!');
        }
        startApplication(true);

        exit(1);
    }

}


