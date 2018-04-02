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
use Table\Menu;

class Manager extends GlobalMap
{

    public function accordion()
    {
        global $json, $forum;

        $json['menu'] = [];
        Menu::All($json['menu'], '');
        return true;
    }

    public function MenuItems() {
        return null;
    }
    public function menu($id)
    {

        global $json, $forum;

        $json['menu'] = [];
        Menu::All($json['menu'], '');
        foreach ($json['menu'] as $key => $value) {
            $json['menu'][$key]['item'] = array();

            Items::All($json['menu'][$key]['item'], $json['menu'][$key]['category_id']);
        }

        if (empty($_POST)) {
            return null;
        }

        switch ($id) {
            case 1:
                Menu::Post(
                    [
                        'category_name' => $forum['category'],
                        'category_description' => $forum['description'],
                        'category_tag' => $forum['tag']
                    ]
                );
                break;
            case 2:
                $id = self::fetch('SELECT category_id FROM RootPrerogative.carbon_menu WHERE category_name = ? LIMIT 1',
                        $forum['category'])['category_id'] ?? false;

                if (!$id) {
                    throw new PublicAlert('warning');
                }

                Items::Post([
                    'category_id' => $id,
                    'item_name' => $forum['dish'],
                    'item_description' => $forum['description'],
                    'item_price' => $forum['price'],
                    'item_calories' => $forum['calories']
                ]);

            default:
        }
        $json['menu'] = array();
        Menu::All($json['menu'], '');
        foreach ($json['menu'] as $key => $value) {
            $json['menu'][$key]['item'] = array();

            Items::All($json['menu'][$key]['item'], $json['menu'][$key]['category_id']);
        }

        return true;
    }

    public function Compensated()
    {
        return true;
    }


    public function Employees()
    {

    }

    public function Costumers()
    {

    }


    public function SalesReport()
    {


        return null;  // SalesReport

    }
}