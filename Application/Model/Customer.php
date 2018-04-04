<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:33 PM
 */

namespace Model;


use Carbon\Request;
use Table\Cart;
use Table\Items;
use Table\Order;

class Customer extends Request
{

    public function cart($id) {
        return null;
    }

    public function order($itemId){
        global $json;

        $json['order'] = [];

        Order::Get($json['order'], $itemId, []);

        /*
        Order::Post([
            'order_total' => []],
            'order_items' => [],
            $array['order_start'],
            $array['order_costumer'],
            $array['order_server'],
            $array['order_notes']);
        ]);
        */

        return null;
    }

    public function item($itemID){

        global $json, $form;
        // TODO - make sure id is valid
        $json['item'] = [];
        Items::Get($json['item'], $itemID, []);

        if ($_POST) {
            Cart::Post([
                'id'=> $itemID,
                'notes' => $form['notes']
            ]);
        }
        return null;
    }

    public function games($game) {
        $game = $this->set($game)->word();

        switch ($game) {
            case 'tetris':
                break;
            default:
                $game = null;
        }

        return null;    // Skip the model and move to the view
    }
}