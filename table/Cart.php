<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 4/2/18
 * Time: 10:27 PM
 */

namespace Table;


use CarbonPHP\Entities;
use CarbonPHP\Error\PublicAlert;
use Model\Helpers\GlobalMap;

class Cart extends Entities
{

    /**
     * @param mixed $array - values received will be placed in this array
     * @param $id - the rows primary key
     * @return bool
     */
    public static function All(array &$array, string $id): bool
    {
        $array = self::fetch('SELECT * FROM RootPrerogative.carbon_cart WHERE session_id = ?',
            $id);

        if (empty($array)) {
            $array = null;
        } else if (!($array[0] ?? false)) {
            $a = $array;
            $array = [];
            $array[] = $a;
        }
        return true;
    }

    /**
     * @param $array - should be set to null on success
     * @param $id - the rows primary key
     * @return bool
     */
    public static function Delete(array &$array, string $id): bool
    {
        return self::execute('DELETE FROM RootPrerogative.carbon_cart WHERE session_id = ?', $id);
    }

    /**
     * @param $array - values received will be placed in this array
     * @param $id - the rows primary key
     * @param $argv - column names desired to be in our array
     * @return bool
     */
    public static function Get(array &$array, string $id, array $argv): bool
    {
        $array = self::fetch('SELECT * FROM RootPrerogative.carbon_cart WHERE session_id = ?',
            $id);

        if (empty($array)) {
            $array = null;
        } else if (!($array[0] ?? false)) {
            $a = $array;
            $array = [];
            $array[] = $a;
        }
        return true;
    }

    /**
     * @param $array - The array we are trying to insert
     * @return bool
     */
    public static function Post(array $array): bool
    {
        self::execute('INSERT INTO RootPrerogative.carbon_cart (cart_id, session_id, cart_item, cart_notes) VALUES (?,?,?,?)',
            self::beginTransaction(CART),
            session_id(),
            $array['id'],
            $array['notes']
        );
        return self::commit(function () {
            GlobalMap::sendUpdate(session_id(), '/cartNotifications');
            PublicAlert::success('added to order');
            return true;
        });
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