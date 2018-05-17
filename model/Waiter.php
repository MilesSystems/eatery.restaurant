<?php
/**
 * Created by IntelliJ IDEA.
 * User: RyanGbo
 * Date: 4/6/2018
 * Time: 1:16 AM
 */

namespace Model;


use Model\Helpers\GlobalMap;

class Waiter extends GlobalMap
{
    public function ViewTables($table_id)
    {

        global $json;


        $json['tables'] = [];

        for ($i = 0; $i < 17; $i++):
            $json['tables'][$i]['name'] = $i;

        endfor;

        $json['tableNumber'] = $this->set($table_id)->int();

        // sortDump($json['tables']);


        if ($json['tableNumber']) {
            $remove = self::fetch('SELECT Count(*) FROM RootPrerogative.carbon_waiter_tables WHERE tableNumber = ? AND userID = ? LIMIT 1',
                $json['tableNumber'],
                $_SESSION['id'])['Count(*)'];


            if ($remove) {
                self::execute('DELETE FROM RootPrerogative.carbon_waiter_tables WHERE tableNumber = ? AND userID = ?',
                    $json['tableNumber'],
                    $_SESSION['id']);

            } else {
                self::execute('INSERT INTO RootPrerogative.carbon_waiter_tables (userID, tableNumber) VALUES (?,?)',
                    $_SESSION['id'],
                    $json['tableNumber']);
            }

        }

        $json['ourTables'] = self::fetch('SELECT * FROM RootPrerogative.carbon_waiter_tables WHERE userID = ?',
            $_SESSION['id']);

        if (!($json['ourTables'][0] ?? false)) {
            $temp = $json['ourTables'];
            $json['ourTables'] = null;
            $json['ourTables'][] = $temp;
        }

        foreach ($json['ourTables'] as $item) {
            if (array_key_exists('tableNumber', $item)) {
                $json['tables'][$item['tableNumber']]['success'] = true;
            }
        }

        return true;
    }
}