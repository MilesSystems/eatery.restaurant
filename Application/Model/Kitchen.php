<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:35 PM
 */

namespace Model;


use Carbon\Error\PublicAlert;
use Model\Helpers\GlobalMap;
use Table\Items;
use Table\Notifications;
use Table\Order;

class Kitchen extends GlobalMap
{
    /**
     * @return bool|null
     */
    public function orders()
    {
        global $json;

        $json['orders'] = [];

        if (!Order::All($json['orders'], session_id())) {
            return null;
        }
        foreach ($json['orders'] as $key => &$value) {
            $value['order_mine'] = $value['order_chef'] === session_id();
            foreach ($value['order_items'] as &$item) {
                Items::Get($item, $item['cart_item'], []);
            }
        }

        return true;
    }

    public function StartOrder($orderId)
    {

        self::execute('UPDATE RootPrerogative.carbon_orders SET order_chef = ? WHERE order_id = ?',
            session_id(),
            $orderId);

        $staff = self::fetchColumn('SELECT session_id FROM RootPrerogative.sessions AS s LEFT JOIN RootPrerogative.carbon_users AS u ON s.user_id = u.user_id WHERE user_type =\'Kitchen\'');

        foreach ($staff as $id) {
            self::sendUpdate($id, 'Kitchen');
        }

        return false;
    }

    public function CompleteOrder($orderID) {

        self::execute('UPDATE RootPrerogative.carbon_orders SET order_chef = ?, order_finish = ? WHERE order_id = ?',
            session_id(),
            date('Y-m-d H:i:s'),
            $orderID);

        $kitchen_staff = self::fetchColumn('SELECT session_id FROM RootPrerogative.sessions JOIN RootPrerogative.carbon_users WHERE user_type = \'kitchen\' AND carbon_users.user_id = sessions.user_id');

        foreach ($kitchen_staff as $staff) {
            GlobalMap::sendUpdate($staff, '/kitchen');
        }

        $waiters = self::fetchColumn('SELECT session_id FROM RootPrerogative.sessions JOIN RootPrerogative.carbon_waiter_tables AS w WHERE w.userID = user_id
                                                      AND w.tableNumber = ?', $_SESSION['table_number']);

        foreach ($waiters as $staff) {
            Notifications::Post([
                'text' => "Order for table #{$_SESSION['table_number']} is ready.",
                'user_session' => $staff
            ]);

            GlobalMap::sendUpdate($staff, '/notifications');
        }

        PublicAlert::success('Order Completed');

        return false;
    }
}