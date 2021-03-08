<?php namespace Primearea\PrimeareaBundle\Dto;

class Cashout
{
    public const STATUS_NEW = 'new';
    public const STATUS_PROCESS = 'process';
    public const STATUS_PERFORMED = 'performed';
    public const STATUS_ERROR = 'error';
    public const STATUS_CANCEL = 'cancel';

    public $id;
    public $userId;
    public $amount;
    public $debit;
    public $status;
    public $paymentAccountId;
    public $externalId;

    public function __construct()
    {
        $this->id = (int)$this->id;
        $this->userId = (int)$this->userId;
        $this->paymentAccountId = empty($this->paymentAccountId) ? null : (int)$this->paymentAccountId;
    }
}
