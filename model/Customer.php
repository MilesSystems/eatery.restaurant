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
use Table\Notifications;
use Table\Order;

class Customer extends GlobalMap
{

    public function completeOrder($orderId, $tip) {

       self::execute('UPDATE RootPrerogative.carbon_orders SET order_tip = ? and order_total = order_total + ? and order_finish = ? WHERE order_id = ?',
           $tip,
           $tip,
           date('Y-m-d H:i:s'),
           $orderId
           );

        startApplication('viewCheck');

        return false;
    }



    /**
     * @return null
     * @throws PublicAlert
     */
    public function refill() {


        $staff = self::fetch('SELECT user_id FROM RootPrerogative.carbon_users JOIN RootPrerogative.carbon_waiter_tables ON userID = user_id AND tableNumber = ?',
            $_SESSION['table_number']);

        Notifications::Post([
            'text' => 'A waiter is on the way!',
            'user_session' => session_id()
        ]);



        self::sendUpdate(session_id(), 'notifications/');

        startApplication(true);
        return false;
    }




    public function help() {

        global $json;

        $staff = self::fetch('SELECT user_id FROM RootPrerogative.carbon_users JOIN RootPrerogative.carbon_waiter_tables ON userID = user_id AND tableNumber = ?',
            $_SESSION['table_number']);

        Notifications::Post([
            'text' => 'A waiter is on the way!',
            'user_session' => session_id()
        ]);

        self::sendUpdate(session_id(), 'notifications/');

        startApplication(true);

        return false;
    }






    public function setTable($id) {

        self::execute('UPDATE RootPrerogative.carbon_users SET user_table_number = ? WHERE user_id = ?',
            $id,
            session_id());

        $_SESSION['table_number'] = $id;

        startApplication(true);
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
            PublicAlert::warning('You must add items to your order first!');
            return false;
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

        PublicAlert::success('Added to cart!');

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

        if (!Order::Get($json['order'], session_id(), [])) {
            return null;
        }

        foreach ($json['order'] as &$order) {
            foreach ($order['order_items'] as $key => &$value) {

                Items::Get($value, $value['cart_item'], []);
                $order['order_subtotal'] = $order['order_total'];
                $order['tax'] = $order['order_total'] * 0.08;
                $order['order_total'] = $order['order_subtotal'] + $order['tax'];

            }
        }
        return null;    // Skip the model and move to the view
    }
}