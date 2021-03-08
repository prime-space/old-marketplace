<?php namespace Primearea\PrimeareaBundle\Transaction;


interface ExecutorInterface
{
    /** @throws PortCannotExecException */
    public function success(int $id);
    public function fail(int $id);
    public function getTransactionType();
    public function getTransaction($transactionId): ?TransactionInterface;
    public function isNeedApply($transaction): bool;
}
