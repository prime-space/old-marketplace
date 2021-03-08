<?php namespace Primearea\PrimeareaBundle\Dto;

class User
{
    public $id;
    public $wmr;
    public $yandex_purse;
    public $qiwi_purse;

    public function __construct()
    {
        $this->id = (int)$this->id;
    }
}
