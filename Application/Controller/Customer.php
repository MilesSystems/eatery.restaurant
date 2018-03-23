<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:33 PM
 */

namespace App\Controller;


use Carbon\Request;

class Customer extends Request
{
    public function games($game) {
        $game = $this->set($game)->word();

        switch ($game) {
            case 'tetris':
                break;
            default:
                $game = null;
        }

        return null;    // Skip the model and move to the view
    }
}