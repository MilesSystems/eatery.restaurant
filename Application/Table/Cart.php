<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 4/2/18
 * Time: 10:27 PM
 */

namespace Table;


use Carbon\Entities;
use Carbon\Interfaces\iTable;

class Cart extends Entities implements iTable
{

    /**
     * @param $array - values received will be placed in this array
     * @param $id - the rows primary key
     * @return bool
     */
    public static function All(array &$array, string $id): bool
    {
        // TODO: Implement All() method.
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
        $array = self::fetch('SELECT * FROM RootPrerogative.session_cart WHERE session_id = ?',
            $id);
        return true;
    }

    /**
     * @param $array - The array we are trying to insert
     * @return bool
     */
    public static function Post(array $array): bool
    {
        return self::execute('INSERT INTO RootPrerogative.session_cart (session_id, cart_item, cart_notes) VALUES (?,?,?)',
            session_id(),
            $array['id'],
            $array['notes']
            );
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