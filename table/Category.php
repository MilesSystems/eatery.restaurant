<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/30/18
 * Time: 6:38 PM
 */

namespace Table;


use Carbon\Entities;
use Carbon\Interfaces\iTable;

class Category extends Entities implements iTable
{


    /**
     * @param $array - values received will be placed in this array
     * @param $id - the rows primary key
     * @return bool
     */
    public static function All(array &$array, string $id): bool
    {
        $array = self::fetch('SELECT * FROM RootPrerogative.carbon_category WHERE category_hidden IS FALSE');

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
        // TODO: Implement Get() method.
    }

    /**
     * @param $array - The array we are trying to insert
     * @return bool
     */
    public static function Post(array $array): bool
    {
        self::execute('INSERT INTO RootPrerogative.carbon_category (category_id, category_name, category_description, category_tag) VALUES (?,?,?,?)',
            self::beginTransaction(CATEGORY),
            $array['category_name'],
            $array['category_description'],
            $array['category_tag']);
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