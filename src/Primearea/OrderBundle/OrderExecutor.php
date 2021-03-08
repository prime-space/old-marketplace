<?php namespace Primearea\OrderBundle;

use Primearea\MerchantBundle\Dto\Transaction;
use Primearea\OrderBundle\Dto\Order;
use Primearea\OrderBundle\Exception\OrderNotFoundException;
use Primearea\OrderBundle\Repository\OrderRepository;
use Primearea\PrimeareaBundle\Port;
use Primearea\PrimeareaBundle\Transaction\ExecutorInterface;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;
use RuntimeException;

class OrderExecutor implements ExecutorInterface
{
    /**
     * @var Port
     */
    private $port;
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @param Port $port
     * @param OrderRepository $orderRepository
     */
    public function __construct(Port $port, OrderRepository $orderRepository)
    {
        $this->port = $port;
        $this->orderRepository = $orderRepository;
    }

    public function success(int $id)
    {
        $this->port->execute('confirm_order', [$id], "$id OK");
    }

    public function fail(int $id)
    {
    }

    public function getTransactionType()
    {
        return Transaction::TYPES['O'];
    }

    public function getTransaction($transactionId): ?TransactionInterface
    {
        $order = $this->orderRepository->findOrder($transactionId);

        if (null === $order) {
            throw new OrderNotFoundException($transactionId);
        }

        return $order;
    }

    public function isNeedApply($transaction): bool
    {
        if (!$transaction instanceof Order) {
            throw new RuntimeException('Expect Payment got something else');
        }

        $needApply = $transaction->status === Order::STATUS_PAY;

        return $needApply;
    }
}
