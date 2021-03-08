<?php namespace Primearea\MerchantBundle\Dto;

class Transaction
{
    public const LIFETIME_MINUTES = 30;
    public const TYPES = ['O' => 'order', 'P' => 'payment'];

    public $id;
    public $type;
    public $received;
    public $paymentAccountId;

    public function __construct()
    {
        $this->id = empty($this->id) ? null : (int)$this->id;
        $this->received = (bool) $this->received;
        $this->paymentAccountId = empty($this->paymentAccountId) ? null : (int)$this->paymentAccountId;
    }
}
