<?php namespace Primearea\MerchantBundle\Store;

use Primearea\MerchantBundle\Dto\QiwiInput;
use Well\DBBundle\DB\DefaultDbClientTrait;

class QiwiInputStore
{
    use DefaultDbClientTrait;

    public function setLastTxnId(int $lastTxnId)
    {
        $this->defaultDbClient->prepare(<<<SQL
UPDATE setting
SET `value` = :lastTxnId
WHERE id = 17
SQL
        )
        ->execute(['lastTxnId' => $lastTxnId]);
    }

    public function create(QiwiInput $qiwiInput)
    {
        $this->defaultDbClient->prepare(<<<SQL
INSERT INTO qiwi_input
  (`txnId`, `status`, `transaction_type`, `transaction_id`)
VALUES
  (:txnId, :status, :transaction_type, :transaction_id)
SQL
        )
            ->execute([
                'txnId' => $qiwiInput->txnId,
                'status' => $qiwiInput->status,
                'transaction_type' => $qiwiInput->transactionType,
                'transaction_id' => $qiwiInput->transactionId,
            ]);

        $id = $this->defaultDbClient->lastInsertId();

        return $id;
    }

    public function markAsHandled(int $id)
    {
        $this->defaultDbClient->prepare(<<<SQL
UPDATE qiwi_input
SET is_handled = 1
WHERE id = :id
SQL
        )
            ->execute([
                'id' => $id,
            ]);
    }
}
