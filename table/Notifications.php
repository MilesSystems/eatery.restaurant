<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 4/5/18
 * Time: 4:41 PM
 */

namespace Table;

use CarbonPHP\Entities;

class Notifications extends Entities
{

    /**
     * @param $array - values received will be placed in this array
     * @param $id - the rows primary key
     * @return bool
     */
    public static function All(array &$array, string $id): bool
    {
        $array = self::fetch('SELECT * FROM carbon_notifications WHERE notification_session = ?', $id);

        if (empty($array)) {
            $array = null;
            return false;
        }

        if(!($array[0] ?? false)) {
            $a = $array;
            $array = [];
            $array[] = $a;
        }

        return true;
    }

    /**
     * @param array|null $array - should be set to null on success
     * @param $id - the rows primary key
     * @return bool
     */
    public static function Delete(array &$array, string $id): bool
    {
        $array = null;
        return self::execute('DELETE FROM carbon_notifications WHERE notification_session = ?', $id);
    }

    /**
     * @param $array - The array we are trying to insert
     * @return bool
     */
    public static function Post(array $array): bool
    {
        self::execute('INSERT INTO carbon_notifications (notification_dismissed, notification_text, notification_session, notification_id) VALUES (?,?,?,?)',
                0,
                $array['text'],
                $array['user_session'],
                self::beginTransaction(NOTIFICATIONS));
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
}