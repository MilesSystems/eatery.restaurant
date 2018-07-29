<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:33 PM
 */

namespace Controller;


use CarbonPHP\Error\PublicAlert;
use CarbonPHP\Request;

class Customer extends Request
{


    public function completeOrder($orderId) {

        $orderId = $this->set($orderId)->alnum();

        $tip = $this->post('')->int();

        return [$orderId, $tip];
    }

    public function cart()
    {
        return true;
    }

    public function refill() {
        return true;
    }

    public function help() {
        return true;
    }

    public function setTable($id) {

        alert('hello');

        if ($id = $this->set($id)->alnum()) {
            return $id;
        }
        return null;

    }

    public function PlaceOrder()
    {
        return true;
    }

    public function ViewCheck()
    {
        global $json;

        $json['DATE'] = date('m/d/Y', strtotime('+2 week',(new \DateTime())->getTimestamp()));

        $json['winner'] = 5 === random_int(0, 10);

        return true;
    }

    public function games($game)
    {
        $game = $this->set($game)->word();

        switch ($game) {
            case 'tetris':
                break;
            default:
                $game = null;
        }

        return null;    // Skip the model and move to the view
    }


    public function item($itemID)
    {
        global $form;

        $form = [];

        $itemID = $this->set($itemID)->alnum();

        if (!$itemID) {
            throw new PublicAlert('This Page Does Not Exist');
        }

        if (!$_POST) {
            return $itemID;
        }

        $form['notes'] = $this->post('notes')->text();

        return $itemID;

    }

    public function MenuItems()
    {
        return null;
    }
}