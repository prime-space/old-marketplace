<?php namespace Primearea\OrderBundle\Dto;

use Primearea\PrimeareaBundle\Transaction\TransactionInterface;

class Order implements TransactionInterface
{
    public const STATUS_PAY = 'pay';

    public $id;
    public $status;
    public $totalBuyer;

    public function getAmount()
    {
        return $this->totalBuyer;
    }
}
