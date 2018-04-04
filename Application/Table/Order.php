<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 4/2/18
 * Time: 3:01 PM
 */

namespace Table;


use Carbon\Entities;
use Carbon\Interfaces\iTable;

class Order extends Entities implements iTable
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
        // TODO: Implement Get() method.
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
                              order_chef, 
                              order_notes) VALUES (?,?,?,?,?,?,?,?,?)',
            self::beginTransaction(ORDER),
            session_id(),
            $array['order_total'],
            json_encode($array['order_items']),
            $array['order_start'],
            $array['order_costumer'],
            $array['order_server'] ?? '',
            $array['order_notes'] ?? '');

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