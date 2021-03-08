<?php namespace Primearea\MerchantBundle\Dto;

use Primearea\PrimeareaBundle\Transaction\TransactionInterface;

class Payment implements TransactionInterface
{
    public const STATUS_FAIL = 'fail';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';

    public $id;
    public $amountPay;
    public $status;

    public function getAmount()
    {
        return $this->amountPay;
    }

}
