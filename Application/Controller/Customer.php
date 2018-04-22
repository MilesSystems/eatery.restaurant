<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:33 PM
 */

namespace Controller;


use Carbon\Error\PublicAlert;
use Carbon\Request;

class Customer extends Request
{

    public function cart()
    {
        return true;
    }

    /**
     * @param $tableNumber
     * @return bool
     * @throws PublicAlert
     */
    public function refill($tableNumber) {
        $this->set($tableNumber)->text();
        if (!$tableNumber) {
            throw new PublicAlert('Failed to find the table number!');
        }
        return true;
    }

    public function setTable($id) {

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