<?php namespace Primearea\PrimeareaBundle\Aggregator\Yandex;

class ScopeError extends APIException
{
    public function __construct()
    {
        parent::__construct('Scope error. Obtain new access_token from user with extended scope', 403);
    }
}
