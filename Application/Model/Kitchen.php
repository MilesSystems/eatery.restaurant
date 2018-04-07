<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:35 PM
 */

namespace Model;


use Model\Helpers\GlobalMap;
use Table\Items;
use Table\Order;

class Kitchen extends GlobalMap
{
    public function orders()
    {
        global $json;

        $json['orders'] = [];

        Order::All($json['orders'], '');

        foreach ($json['orders'] as $key => &$value) {
            foreach ($value['order_items'] as &$item) {
                Items::Get($item, $item['cart_item'], []);
            }
        }

    }

    public function StartOrder($orderId)
    {

        self::execute('UPDATE RootPrerogative.carbon_orders SET order_chef = ? WHERE order_id = ?',
            session_id(),
            $orderId);

        $staff = self::fetchColumn('SELECT user_session_id FROM RootPrerogative.carbon_users WHERE user_type =\'Kitchen\' AND user_id != ?', session_id());

        foreach ($staff as $id) {
            self::sendUpdate($id, 'orders');
        }
        self::sendUpdate(session_id(), 'orders');
        return true;
    }

}