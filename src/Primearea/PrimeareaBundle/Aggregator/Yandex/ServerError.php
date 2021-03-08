<?php namespace Primearea\PrimeareaBundle\Aggregator\Yandex;

class ServerError extends APIException
{
    public function __construct($status_code)
    {
        parent::__construct('Server error', $status_code);
    }
}
