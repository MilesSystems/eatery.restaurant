<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 4/2/18
 * Time: 3:01 PM
 */

namespace Table;


use Carbon\Database;
use Carbon\Entities;
use Carbon\Error\PublicAlert;
use Carbon\Interfaces\iTable;
use Model\Helpers\GlobalMap;

class Order extends Entities implements iTable
{

    /**
     * @param $array - values received will be placed in this array
     * @param $id - the rows primary key
     * @return bool
     */
    public static function All(array &$array, string $id): bool
    {
        $array = self::fetch('SELECT * FROM RootPrerogative.carbon_orders WHERE order_chef IS NULL OR carbon_orders.order_chef = ? AND carbon_orders.order_finish IS NULL ',
            $id);

        if (!($array[0] ?? false)) {
            if (empty($array)) {
                return false;
            }
            $a = $array;
            $array = [];
            $array[] = $a;
        }

        foreach ($array as &$value) {
            $value['order_items'] = !empty($value['order_items']) ? json_decode($value['order_items'], TRUE) : false;
        }

        return true;
    }

    /**
     * @param $array - should be set to null on success
     * @param $id - the rows primary key
     * @return bool
     */
    public static function Delete(array &$array, string $id): bool
    {
        // TODO: Implement Delete() method.
    }

    /**
     * @param $array - values received will be placed in this array
     * @param $id - the rows primary key
     * @param $argv - column names desired to be in our array
     * @return bool
     */
    public static function Get(array &$array, string $id, array $argv): bool
    {
        $array = self::fetch('SELECT * FROM RootPrerogative.carbon_orders WHERE order_session = ? AND order_finish IS NULL',
            $id);


        if (!$array[0] ?? false){
            if (empty($array)) {
                return false;
            }
            $a = $array;
            $array = null;
            $array[0]= $a;
        }

        foreach ($array as &$value) {
            $value['order_items'] = !empty($value['order_items']) ? json_decode($value['order_items'], TRUE) : false;
        }

        return true;
    }

    /**
     * @param $array - The array we are trying to insert
     * @return bool
     */
    public static function Post(array $array): bool
    {
        self::execute('INSERT INTO RootPrerogative.carbon_orders (
                              order_id,  
                              order_session, 
                              order_total, 
                              order_items, 
                              order_start, 
                              order_costumer, 
                              order_server,  
                              order_notes) VALUES (?,?,?,?,?,?,?,?)',
            self::beginTransaction(ORDER),
            session_id(),
            $array['order_total'],
            json_encode($array['order_items']),
            $array['order_start'],
            $_SESSION['id'] ?? '',
            $array['order_server'] ?? '',
            $array['order_notes'] ?? '');

        return self::commit(function () {
            $cart = [];
            Cart::Delete($cart, session_id());
            PublicAlert::success('added to order');
            Notifications::Post([
                'text' => 'Your order has been received!',
                'user_session' => session_id()
            ]);

            $kitchen_staff = self::fetchColumn('SELECT user_session_id FROM RootPrerogative.carbon_users WHERE user_type = \'kitchen\'');

            foreach ($kitchen_staff as $staff) {
                Notifications::Post([
                    'text' => "Table {$_SESSION['table_number']} has placed an order.",
                    'user_session' => $staff
                ]);

                GlobalMap::sendUpdate($staff, '/notifications');

                GlobalMap::sendUpdate($staff, '/kitchen');
            }

            $_SESSION['table_number'] = $_SESSION['table_number'] ?? null;

            $waiters = self::fetchColumn('SELECT session_id FROM RootPrerogative.sessions JOIN RootPrerogative.carbon_waiter_tables AS w WHERE w.userID = user_id
                                                      AND w.tableNumber = ?', $_SESSION['table_number']);

            foreach ($waiters as $staff) {
                Notifications::Post([
                    'text' => "Table {$_SESSION['table_number']} has placed an order.",
                    'user_session' => $staff
                ]);

                GlobalMap::sendUpdate($staff, '/notifications');
            }



            GlobalMap::sendUpdate(session_id(), '/cartNotifications');
            GlobalMap::sendUpdate(session_id(), '/notifications');
            return true;
        });

    }

    /**
     * @param $array - on success, fields updated will be
     * @param $id - the rows primary key
     * @param $argv - an associative array of Column => Value pairs
     * @return bool  - true on success false on failure
     */
    public static function Put(array &$array, string $id, array $argv): bool
    {
        return self::execute('UPDATE RootPrerogative.carbon_orders SET order_id = ? WHERE order_finish = ?', $id, date('m/d/Y', strtotime('+2 week',(new \DateTime())->getTimestamp())));
    }
}