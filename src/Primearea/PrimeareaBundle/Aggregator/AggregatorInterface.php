<?php namespace Primearea\PrimeareaBundle\Aggregator;

use Exception;
use Primearea\PrimeareaBundle\Dto\Cashout;
use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Primearea\PrimeareaBundle\Exception\WithdrawException;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;

interface AggregatorInterface
{
    public function check(TransactionInterface $transaction, $paidAmount, $currency);
    public function isWithdrawEnabled(): bool;
    /** @throws WithdrawException */
    public function getPaymentAccount(): PaymentAccountDto;
    public function calculateWithdraw($amount, $fee): array;
    public function getPayoutFee();
    /** @throws WithdrawException */
    public function getPurse(Cashout $cashout): string;
    public function getPrimePayerMethodName(): string;
}
