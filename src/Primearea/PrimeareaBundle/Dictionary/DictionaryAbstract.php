<?php namespace Primearea\PrimeareaBundle\Dictionary;

abstract class DictionaryAbstract
{
    public function hasKey($key)
    {
        return isset($this->items[$key]);
    }
}
