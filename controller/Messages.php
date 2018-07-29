<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 12/2/17
 * Time: 3:56 PM
 */

namespace Controller;

use CarbonPHP\Error\PublicAlert;
use CarbonPHP\Request;
use Table\Messages as Table;
use Table\Users as U;

class Messages extends Request
{
    /**
     * @return bool
     * @throws PublicAlert
     */
    public function mail(): bool
    {

        if (empty($_POST)) {
            return false;
        }

        if (!$email = $this->post('email')->email()) {
            throw new PublicAlert('You must provide a valid email!');
        }

        if (!$subject = $this->post('subject')->noHTML(true)) {
            throw new PublicAlert('Please set a subject!');
        }

        if (!$message = $this->post('message')->noHTML(true)) {
            throw new PublicAlert('Please type out a message to send Richard@Miles.Systems');
        }

        $message = $email . PHP_EOL . $message;

        $message = wordwrap($message, 70, "\r\n");

        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        $headers[] = 'To: Richard <Richard@miles.systems>';
        $headers[] = 'From: WebSender <support@miles.systems>';
        $headers[] = 'Cc: tmiles199@gmail.com';
        $headers[] = 'Bcc: Richard@miles.systems';


        if (!mail('Richard@miles.systems', $subject, $message, implode("\r\n", $headers))) {
            PublicAlert::success('Want advice now! Give me a call at 817-789-3294.');
        } else {
            PublicAlert::success('Thank you for reaching out, we will get back to you ASAP!');
        }

        #Reports::Post(['level' => 'Mail', 'report' => $message]);       //  TODO - send mail

        return false;   // There is nothing in the model to run
    }


    public function messages() {
        // list($us_id, $messages) = $this->post('user_id','message')->alnum();
        return true;
    }

    public function navigation() {
        return true;
    }

    public function chat($user_uri = false){
        global $user_id;

        $user_id = U::user_id_from_uri($user_uri) or die(1);        // if post isset we can assume an add

        if (!empty($_POST) && !empty($string = $this->post('message')->noHTML()->value())) {
            Table::Post($this->user[$user_id], $user_id, $string);
        }     // else were grabbing content (json, html, etc)

        Table::All($this->user[$user_id], $user_id);

        return true;
    }
}