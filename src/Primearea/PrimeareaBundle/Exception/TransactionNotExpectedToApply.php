<?php namespace Primearea\PrimeareaBundle\Exception;

use Exception;
use Throwable;

class TransactionNotExpectedToApply extends Exception
{
    public function __construct($case)
    {
        $message = "Transaction not expected to apply ($case)";
        parent::__construct($message);
    }
}
