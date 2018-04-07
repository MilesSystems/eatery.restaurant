<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:33 PM
 */

namespace Model;


use Carbon\Error\PublicAlert;
use Model\Helpers\GlobalMap;
use Table\Cart;
use Table\Items;
use Table\Order;

class Customer extends GlobalMap
{

    /**
     * @return null
     * @throws PublicAlert
     */
    public function refill($tableNumber) {

        global $json;

        $staff = self::fetch('SELECT user_id, user_tables FROM RootPrerogative.carbon_users WHERE user_type = \'Waiter\' AND user_tables IS NOT NULL');

        if (empty($staff)) {
            throw new PublicAlert('Sorry, an Error Has Occured');
        }


        if (!$staff[0] ?? false) {
            $staff['user_tables'] = json_decode($staff['user_tables']);
        } else {
            foreach ($staff as $key => $employee) {
                // TODO - implement
            }
        }

        $staff['user_tables'] = json_decode($staff['user_tables']);

        self::sendUpdate(session_id(), 'notifications/');

        return null;
    }


    public function setTable($id) {

        self::execute('UPDATE RootPrerogative.carbon_users SET user_tables = ? WHERE user_id = ?',
            $id,
            session_id());

    }

    public function cart() {
        global $json;
        $json['items'] = [];

        Cart::Get($json['items'], session_id(), []);

        if (empty($json['items'])) {
            unset($json['items']);
            return null;
        }

        if (!($json['items'][0] ?? false)) {
            $a = $json['items'];
            $json['items'] = [];
            $json['items'][] = $a;
        }

        $json['cartNotifications'] = \count($json['items']);

        foreach($json['items'] as $key => $value) {
            Items::Get($json['items'][$key], $value['cart_item'], []);
        }

        return null;
    }

    /**
     * @return null
     * @throws PublicAlert
     */
    public function placeOrder(){
        global $json;

        $json['items'] = [];

        $json['order'] = [];

        Cart::All($json['order'], session_id());

        if (!$json['order']) {
            throw new PublicAlert('You must add items to your order first!');
        }

        $total = 0;
        $notes = '';

        foreach ($json['order'] as $key => $value) {
            Items::Get($json['items'], $value['cart_item'], []);
            $total += $json['items']['item_price'];
            $notes .= PHP_EOL . ($json['items']['cart_notes'] ?? '');
        }

        if (!Order::Post([
            'order_items' => $json['order'],
            'order_total' => $total,
            'order_start' =>  date('Y-m-d H:i:s'),
            'order_notes' => $notes
        ])) {
            throw new PublicAlert('Could not post to Database :(');
        }

        Order::Get($json['order'], session_id(), []);

        return true;
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

        self::sendUpdate(session_id(), '/cartNotifications');

        return null;
    }

    public function viewCheck() {
       global $json;

        $json['order'] = [];

        Order::Get($json['order'], session_id(), []);

        if (!$json['order']['order_items']) {
            unset($json['order']);
            return null;
        }

        foreach($json['order']['order_items'] as $key => &$value) {
            Items::Get($value, $value['cart_item'], []);
        }

        $json['order']['order_subtotal'] = $json['order']['order_total'];
        $json['order']['tax'] = $json['order']['order_total'] * 0.08;
        $json['order']['order_total'] = $json['order']['order_subtotal'] + $json['order']['tax'];

        return null;    // Skip the model and move to the view
    }
}