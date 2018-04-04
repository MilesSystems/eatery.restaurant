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
        return null;
    }

    public function order($itemId)
    {
        $itemId = $this->set($itemId)->alnum();

        if (!$itemId) {
            throw new PublicAlert('Could not add to order, please try again!');
        }

        if (!$_POST) {
            return $itemId;
        }
        print 'ffjkldsa;fjkldsa;' . PHP_EOL . PHP_EOL;

        return $itemId;
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