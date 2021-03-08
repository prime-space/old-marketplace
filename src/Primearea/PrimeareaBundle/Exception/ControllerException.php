<?php namespace Primearea\PrimeareaBundle\Exception;

use Exception;

class ControllerException extends Exception
{
    public function __construct($code)
    {
        parent::__construct('', $code);
    }
}
