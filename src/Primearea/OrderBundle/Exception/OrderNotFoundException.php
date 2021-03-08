<?php namespace Primearea\OrderBundle\Exception;

use Exception;

class OrderNotFoundException extends Exception
{
    public function __construct($orderId)
    {
        parent::__construct("Order #$orderId not found");
    }
}
