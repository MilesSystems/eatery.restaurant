<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:35 PM
 */

namespace Controller;


use Carbon\Request;

class Kitchen extends Request
{
    public function orders() {
        return true;
    }


    public function StartOrder($orderId) {

        return $orderId;
    }


}