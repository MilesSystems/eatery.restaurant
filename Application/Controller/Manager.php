<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:35 PM
 */

namespace Controller;


use Carbon\Error\PublicAlert;
use Carbon\Request;

class Manager extends Request
{

    public function accordion() {

        print 'fjsdklaf' . PHP_EOL;
        return true;
    }


    public function Compensated() {
        return null;
    }

    public function menu($form)
    {
        if (empty($_POST)) {
            return true;
        }

        global $forum;

        $forum = array();

        switch ($this->set($form)->int(0,1)) {
            case 2:
                ################ New Item

                $forum['dish'] = $this->post('dish')->text();
                $forum['category'] = $this->post('category')->text();

                if (!$forum['category'] || !$forum['dish']) {
                    throw new PublicAlert('Forum fields must be alpha numberic');
                }
                return 2;
            case 1:
                ################ New Category

                $forum['category'] = $this->post('category')->text();
                $forum['description'] = $this->post('description')->text();
                $forum['tag'] = $this->post('tag')->text();

                if (!$forum['category']) {
                    throw new PublicAlert('The category name must be alpha numberic');
                }
                return 1;
            default:
                return null;
        }
    }

    public function Employees() {
        return null;
    }

    public function Costumers() {
        return null;
    }

    public function SalesReport() {
        return null;  // SalesReport
    }
}