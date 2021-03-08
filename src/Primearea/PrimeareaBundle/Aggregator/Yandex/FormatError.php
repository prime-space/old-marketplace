<?php namespace Primearea\PrimeareaBundle\Aggregator\Yandex;

class FormatError extends APIException
{
    public function __construct()
    {
        parent::__construct('Request is missformated', 400);
    }
}
