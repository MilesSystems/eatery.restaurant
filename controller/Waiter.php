<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:35 PM
 */

namespace Controller;


use CarbonPHP\Request;

class Waiter extends Request
{

    public function ViewTables($table_id)
    {
        global $json;

        $json['tableNumber'] = $this->set($table_id)->int();

        return true;
    }
}