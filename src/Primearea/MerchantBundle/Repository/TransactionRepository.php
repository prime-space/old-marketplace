<?php namespace Primearea\MerchantBundle\Repository;

use Primearea\MerchantBundle\Dto\Payment;
use Primearea\MerchantBundle\Dto\PaySys;
use Primearea\MerchantBundle\Dto\Transaction;
use Primearea\OrderBundle\Dto\PayMethod;
use Well\DBBundle\DB\DefaultDbClientTrait;
use Well\DBBundle\Exception\EntryNotFoundException;

class TransactionRepository
{
    use DefaultDbClientTrait;

    /**
     * @return Transaction[]
     */
    public function getLastTransactions(): array
    {
        $transactionTypePayment = Transaction::TYPES['P'];
        $transactionTypeOrder = Transaction::TYPES['O'];
        $paySysQiwiId = PaySys::QIWI_ID;
        $payMethodQiwiId = PayMethod::QIWI_ID;
        $paymentStatusPending = Payment::STATUS_PENDING;
        $paymentStatusSuccess = Payment::STATUS_SUCCESS;
        $paymentStatusFail = Payment::STATUS_FAIL;
        $transactionLifetime = Transaction::LIFETIME_MINUTES;

        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT mp.id, '$transactionTypePayment' as type, qi1.id as received, mp.payment_account_id as paymentAccountId
FROM mpayments mp
LEFT JOIN qiwi_input qi1 ON
    qi1.transaction_id = mp.id
     AND qi1.transaction_type = '$transactionTypePayment'
WHERE
    mp.viaId = $paySysQiwiId
    AND mp.status IN ('$paymentStatusPending', '$paymentStatusSuccess', '$paymentStatusFail')
    AND mp.ts > ADDDATE(NOW(), INTERVAL -$transactionLifetime MINUTE)
    

UNION ALL

SELECT o.id, '$transactionTypeOrder' as type, qi2.id as received, payment_account_id as paymentAccountId
FROM `order` o
LEFT JOIN qiwi_input qi2 ON
    qi2.transaction_id = o.id
    AND qi2.transaction_type = '$transactionTypeOrder'
WHERE
    o.date > ADDDATE(NOW(), INTERVAL -$transactionLifetime MINUTE)
    AND o.pay_method_id = $payMethodQiwiId
SQL
            )
            ->execute();

        $transactions = [];
        try {
            while ($transaction = $statement->fetchObject(Transaction::class)) {
                $transactions[] = $transaction;
            }
        } catch (EntryNotFoundException $e) {
        }

        return $transactions;
    }
}
