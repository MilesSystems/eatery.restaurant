<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/30/18
 * Time: 7:36 PM
 */

namespace Table;

use CarbonPHP\Entities;

class Items extends Entities
{
    /**
     * @param $array - values received will be placed in this array
     * @param $id - the rows primary key
     * @return bool
     */
    public static function All(array &$array, string $id): bool
    {
            $array = self::fetch('SELECT i.* FROM RootPrerogative.category_items AS i 
                            LEFT JOIN RootPrerogative.carbon AS c ON i.item_id = c.entity_pk
                            LEFT JOIN RootPrerogative.carbon_category ON category_id = c.entity_fk
                            WHERE category_id = ?', $id);
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
        $array = array_merge($array,self::fetch('SELECT * FROM RootPrerogative.category_items WHERE item_id = ?',
            $id));

        return true;
    }

    /**
     * @param $array - The array we are trying to insert
     * @return bool
     */
    public static function Post(array $array): bool
    {
        //sortDump($array['category_id']);

        self::execute('INSERT INTO RootPrerogative.category_items (item_id, item_name, item_description, item_price, item_calories) VALUES (?,?,?,?,?)',
            self::beginTransaction(ITEMS, $array['category_id']),
            $array['item_name'],
            $array['item_description'],
            $array['item_price'],
            $array['item_calories']);
        return self::commit();
    }

    /**
     * @param $array - on success, fields updated will be
     * @param $id - the rows primary key
     * @param $argv - an associative array of Column => Value pairs
     * @return bool  - true on success false on failure
     */
    public static function Put(array &$array, string $id, array $argv): bool
    {
        // TODO: Implement Put() method.
    }
}