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
    public function ViewTables() {

        global $json;

        if ($json['tableNumber']) {
            self::execute('INSERT INTO RootPrerogative.carbon_waiter_tables (userID, tableNumber) VALUES (?,?)',
                $_SESSION['id'],
                $json['tableNumber']){
            $json['tableNumber']
            };
        }

        $json['ourTables'] = self::fetch('SELECT * FROM RootPrerogative.carbon_waiter_tables WHERE userID = ?',
            $_SESSION['id']);


        foreach ($json['ourTables'] as $item) {
            $json['tables'][$item['tableNumber']]['success'] = true;
        }

        return true;
    }
}