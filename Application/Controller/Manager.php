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

    public function accordion()
    {
        return null;
    }


    public function Compensated()
    {
        return null;
    }

    public function menu($id)
    {
        if (empty($_POST)) {
            return true;
        }

        global $forum;

        $forum = array();

        switch ($this->set($id)->int(1, 2)) {

            case 1:
                ################ New Category

                $forum['category'] = $this->post('category')->text();
                $forum['description'] = $this->post('description')->text();
                $forum['tag'] = $this->post('tag')->text();

                if (!$forum['category']) {
                    throw new PublicAlert('The category name must be alpha numberic');
                }
                return 1;
            case 2:
                ################ New Item

                $forum['dish'] = $this->post('dish')->text();
                $forum['category'] = $this->post('category')->text();
                $forum['description'] = $this->post('description')->text();
                $forum['price'] = $this->post('price')->text();
                $forum['calories'] = $this->post('calories')->text();

                if (!$forum['category'] || !$forum['dish'] ||
                    !$forum['description'] || !$forum['price'] || !$forum['calories']) {
                    throw new PublicAlert('Forum fields must be alpha numeric');
                }
                return 2;
            default:
                return null;
        }
    }

    public function Employees()
    {
        return null;
    }

    public function Costumers()
    {
        return null;
    }

    public function SalesReport()
    {
        return null;  // SalesReport
    }
}