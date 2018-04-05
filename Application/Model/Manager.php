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

class Manager extends GlobalMap
{

    public function accordion()
    {
        global $json, $forum;

        $json['menu'] = [];
        Category::All($json['menu'], '');
        return true;
    }

    public function MenuItems() {
        return null;
    }
    public function menu($id)
    {

        global $json, $forum;

        $json['category'] = [];
        Category::All($json['category'], '');
        foreach ($json['category'] as $key => $value) {
            $json['category'][$key]['item'] = array();
<<<<<<< HEAD
            Items::All($json['category'][$key]['item'], $json['category'][$key]['category_id']);
        }

=======

            Items::All($json['category'][$key]['item'], $json['category'][$key]['category_id']);
        }

>>>>>>> 5a50d70ff35c37d473decaf542cf34f01c638066
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
<<<<<<< HEAD
                $id = self::fetch('SELECT category_id FROM RootPrerogative.carbon_category WHERE category_name = ? LIMIT 1',
                        $forum['category'])['category_id'] ?? false;

                if (!$id) {
                    throw new PublicAlert('warning');
                }

                Items::Post(
                    [
=======
                $id = self::fetch('SELECT category_id FROM RootPrerogative.carbon_menu WHERE category_name = ? LIMIT 1',
                        $forum['category'])['category_id'] ?? false;

                if (!$id) {
                    throw new PublicAlert('warning');
                }

                Items::Post([
>>>>>>> 5a50d70ff35c37d473decaf542cf34f01c638066
                    'category_id' => $id,
                    'item_name' => $forum['dish'],
                    'item_description' => $forum['description'],
                    'item_price' => $forum['price'],
                    'item_calories' => $forum['calories']
<<<<<<< HEAD
                    ]
                );
=======
                ]);
>>>>>>> 5a50d70ff35c37d473decaf542cf34f01c638066

            default:
        }
        $json['category'] = array();
        Category::All($json['category'], '');
        foreach ($json['category'] as $key => $value) {
            $json['category'][$key]['item'] = array();
            Items::All($json['category'][$key]['item'], $json['menu'][$key]['category_id']);
        }

<<<<<<< HEAD
        $json['category'] = array();
        Category::All($json['category'], '');
        foreach ($json['category'] as $key => $value) {
            $json['category'][$key]['item'] = array();
            Items::All($json['category'][$key]['item'], $json['category'][$key]['category_id']);
        }

=======
>>>>>>> 5a50d70ff35c37d473decaf542cf34f01c638066
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