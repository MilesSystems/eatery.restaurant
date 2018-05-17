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
use Table\Category;
use Table\Order;
use Table\Users;

class Manager extends GlobalMap
{

    public function messages($us_id, $messages) {

        global $json, $user;

        $json['widget'] = '#NavMessages';

        foreach ($user as $id => $info) {
            if ($id === $_SESSION['id'])
                continue;
            $json['users'][] = array(
                'user_id' => $info['user_id'],
                'user_profile_pic' => $info['user_profile_pic'],
                'user_profile_url' => $info['user_profile_uri'],
                'user_full_name' => $info['user_full_name'],
                'user_last_login' => date('D, d M Y', $info['user_last_login'])
            );
        }
    }

    public function hideCategory($id) {
        self::execute('UPDATE RootPrerogative.carbon_category SET category_hidden = TRUE WHERE category_id = ?', $id);
        self::sendUpdate(session_id(), 'Menu');
        return false;
    }

    public function accordion()
    {
        global $json, $forum;

        $json['menu'] = [];
        Category::All($json['menu'], '');
        return true;
    }

    public function MenuItems()
    {
        return null;
    }

    public function menu($id)
    {

        global $json, $forum;

        $json['category'] = [];
        Category::All($json['category'], '');
        foreach ($json['category'] as $key => $value) {
            $json['category'][$key]['item'] = array();
            Items::All($json['category'][$key]['item'], $json['category'][$key]['category_id']);
        }

        if (empty($_POST)) {
            return null;
        }

        switch ($id) {
            case 1:
                Category::Post(
                    [
                        'category_name' => $forum['category'],
                        'category_description' => $forum['description'],
                        'category_tag' => $forum['tag']
                    ]
                );
                break;
            case 2:
                $id = self::fetch('SELECT category_id FROM RootPrerogative.carbon_category WHERE category_name = ? LIMIT 1',
                        $forum['category'])['category_id'] ?? false;

                if (!$id) {
                    throw new PublicAlert('warning');
                }

                Items::Post(
                    [
                        'category_id' => $id,
                        'item_name' => $forum['dish'],
                        'item_description' => $forum['description'],
                        'item_price' => $forum['price'],
                        'item_calories' => $forum['calories']
                    ]
                );

            default:
        }

        $json['category'] = array();
        Category::All($json['category'], '');
        foreach ($json['category'] as $key => $value) {
            $json['category'][$key]['item'] = array();
            Items::All($json['category'][$key]['item'], $json['category'][$key]['category_id']);
        }

        return true;
    }

    public function Compensated()
    {
        global $json;
        $json['compensated_items'] = self::fetch('SELECT * FROM RootPrerogative.carbon_compensated');

        return true;
    }

    public function Employees()
    {
        global $json;

        $json['users'] = self::fetch('SELECT * FROM RootPrerogative.carbon_users WHERE user_type != \'Customer\'');

        foreach ($json['users'] as &$user) {
            Users::userDefaults($user, $user['user_id']);
        }
        unset($user);

        $json['totalEmployees'] = \count($json['users']);

        return true;
    }

    public function customers()
    {
        global $json;

        $json['users'] = self::fetch('SELECT * FROM RootPrerogative.carbon_users WHERE user_type = \'Customer\'');

        foreach ($json['users'] as &$user) {
            Users::userDefaults($user, $user['user_id']);
        }
        unset($user);

        $json['totalCustomers'] = \count($json['users']);

        return true;
    }

    public function changeType($user_id, $user_type)
    {
        self::execute('UPDATE RootPrerogative.carbon_users SET user_type = ? WHERE user_id = ?',
            $user_type,
            $user_id);

        //GlobalMap::sendUpdate(session_id(), 'home');

        return false;   // startApplication(true)
    }

    public function SalesReport(){
        global $json;
        $json['order'] = [];
        Order::All($json['order'], '');
        return true;  // SalesReport

    }
}