<?php namespace Primearea\MerchantBundle\Store;

use Primearea\MerchantBundle\Dto\Payment;
use Well\DBBundle\DB\DefaultDbClientTrait;

class PaymentStore
{
    use DefaultDbClientTrait;

    public function markPaymentAsFail(int $paymentId)
    {
        $this->defaultDbClient->prepare(<<<SQL
UPDATE mpayments
SET `status` = :status
WHERE id = :id
SQL
        )
        ->execute([
            'id' => $paymentId,
            'status' => Payment::STATUS_FAIL,
        ]);
    }
}
