<?php namespace Primearea\MerchantBundle\Dto;

class QiwiInput
{
    public $id;
    public $txnId;
    public $status;
    public $transactionType;
    public $transactionId;
    public $isHandled;
    public $createdTs;

    public static function create(
        array $transaction,
        string $transactionType = null,
        int $transactionId = null
    ): self {
        $qiwiInput = new self();
        $qiwiInput->txnId = $transaction['txnId'];
        $qiwiInput->status = $transaction['status'];
        $qiwiInput->transactionType = $transactionType;
        $qiwiInput->transactionId = $transactionId;
        $qiwiInput->isHandled = false;

        return $qiwiInput;
    }
}
