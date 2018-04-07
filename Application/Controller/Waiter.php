<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:35 PM
 */

namespace Controller;


use Carbon\Request;

class Waiter extends Request
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
        return true;
    }
}