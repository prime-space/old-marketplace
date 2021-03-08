<?php namespace Primearea\MerchantBundle\Payment;

use Primearea\MerchantBundle\Dto\Payment;
use Primearea\MerchantBundle\Dto\Transaction;
use Primearea\MerchantBundle\Exception\PaymentNotFoundException;
use Primearea\MerchantBundle\Repository\PaymentRepository;
use Primearea\PrimeareaBundle\Port;
use Primearea\PrimeareaBundle\Transaction\ExecutorInterface;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;
use RuntimeException;

class PaymentExecutor implements ExecutorInterface
{
    /**
     * @var Port
     */
    private $port;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    /**
     * @param Port $port
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(Port $port, PaymentRepository $paymentRepository)
    {
        $this->port = $port;
        $this->paymentRepository = $paymentRepository;
    }

    public function success(int $id)
    {
        $this->port->execute('confirm_payment', [$id], "$id OK");
    }

    public function fail(int $id)
    {
        // TODO: Implement fail() method.
    }

    public function getTransactionType()
    {
        return Transaction::TYPES['P'];
    }

    public function getTransaction($transactionId): ?TransactionInterface
    {
        $payment = $this->paymentRepository->findPayment($transactionId);

        if (null === $payment) {
            throw new PaymentNotFoundException($transactionId);
        }

        return $payment;
    }

    public function isNeedApply($transaction): bool
    {
        if (!$transaction instanceof Payment) {
            throw new RuntimeException('Expect Payment got something else');
        }

        $needApply = $transaction->status === Payment::STATUS_PENDING;

        return $needApply;
    }
}
