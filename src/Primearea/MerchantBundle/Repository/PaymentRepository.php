<?php namespace Primearea\MerchantBundle\Repository;

use Primearea\MerchantBundle\Dto\Payment;
use Well\DBBundle\DB\DefaultDbClientTrait;
use Well\DBBundle\Exception\EntryNotFoundException;

class PaymentRepository
{
    use DefaultDbClientTrait;

    public function getPaymentStatus(int $paymentId): string
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT `status`
FROM mpayments
WHERE id = :id
SQL
            )
            ->execute([
                'id' => $paymentId,
            ]);

        $status = $statement->fetchColumn();

        return $status;
    }

    public function findPayment(int $id): ?Payment
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT id, amountPay, `status`
FROM mpayments
WHERE id = :id
SQL
            )
            ->execute(['id' => $id]);

        try {
            /** @var Payment $payment */
            $payment = $statement->fetchObject(Payment::class);

            return $payment;
        } catch (EntryNotFoundException $e) {
            return null;
        }
    }
}
