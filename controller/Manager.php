<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/23/18
 * Time: 12:35 PM
 */

namespace Controller;


use CarbonPHP\Error\PublicAlert;
use CarbonPHP\Request;
use Table\Order;

class Manager extends Request
{

    public function messages()
    {
        [$us_id, $messages] = $this->post('user_id', 'message')->alnum();
        return [$us_id, $messages];

    }

    public function hideCategory($id)
    {
        return $id;
    }

    public function accordion()
    {
        return null;
    }


    public function Compensated()
    {
        return true;
    }

    /**
     * @param $id
     * @return bool|int|null
     * @throws PublicAlert
     */
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
                $forum['tag'] = trim($this->post('tag')->text());

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

                if (false === $forum['category'] || false === $forum['dish'] ||
                    false === $forum['description'] || false === $forum['price'] || false === $forum['calories']) {
                    throw new PublicAlert('Form fields must be alpha numeric');
                }
                return 2;
            default:
                return null;
        }
    }

    public function Employees()
    {
        return true;
    }

    public function customers()
    {
        return true;
    }

    /**
     * @param $user_id
     * @param $user_type
     * @return array
     * @throws PublicAlert
     */
    public function changeType($user_id, $user_type)
    {

        [$user_id, $user_type] = $this->set($user_id, $user_type)->text();

        switch ($user_type) {
            case 'Manager':
            case 'Waiter':
            case 'Kitchen':
            case 'Customer' :
                break;
            default:
                throw new PublicAlert('The User Type Appears Invalid');
        }


        if (!$user_id) {
            throw new PublicAlert('The User ID Appears Invalid');
        }


        return [$user_id, $user_type];
    }

    public function SalesReport()
    {
        return true;  // SalesReport
    }
}