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
use Table\Menu;

class Manager extends GlobalMap
{

    public function accordion() {
        global $json, $forum;

        $json['categories'] = [];
        Menu::All($json['categories'], '');
        return true;
    }


    public function menu($form)
    {

        global $json, $forum;

        $json['categories'] = [];
        Menu::All($json['categories'], '');
        //sortDump($json);

        if (empty($forum)) {
            return null;
        }

        switch ($form) {
            case 1:
                Menu::Post(
                    [
                        'category_name' => $forum['category'],
                        'category_description' => $forum['description'],
                        'category_tag' => $forum['tag']
                    ]
                );

                return true;
            case 2:
                $id = self::fetch('SELECT category_id FROM RootPrerogative.carbon_menu WHERE category_name = ?',
                    $forum['category']);

                sortDump($id);

                Items::Post([
                    'item_name' => '',
                    'item_description' => '',
                    'item_price' => '',
                    'item_calories' => '',

                ]);

                return true;
            default:
        }

        Menu::All($json, '');

        sortDump($json);

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