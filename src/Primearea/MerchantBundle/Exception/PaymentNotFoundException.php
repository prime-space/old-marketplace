<?php namespace Primearea\MerchantBundle\Exception;

use Exception;

class PaymentNotFoundException extends Exception
{
    public function __construct($paymentId)
    {
        parent::__construct("Payment #$paymentId not found");
    }
}
