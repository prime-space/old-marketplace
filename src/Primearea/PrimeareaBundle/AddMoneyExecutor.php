<?php namespace Primearea\PrimeareaBundle;

use Primearea\PrimeareaBundle\Transaction\ExecutorInterface;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;
use RuntimeException;

class AddMoneyExecutor implements ExecutorInterface
{
    private $port;

    public function __construct(Port $port)
    {
        $this->port = $port;
    }

    /** @inheritdoc */
    public function success(int $id)
    {
        $this->port->execute('confirm_addmoney', [$id], "$id OK");
    }

    public function fail(int $id)
    {
        throw new RuntimeException('Method not implemented');
    }

    public function getTransactionType()
    {
        throw new RuntimeException('Method not implemented');
    }

    public function getTransaction($transactionId): ?TransactionInterface
    {
        throw new RuntimeException('Method not implemented');
    }

    public function isNeedApply($transaction): bool
    {
        throw new RuntimeException('Method not implemented');
    }
}
