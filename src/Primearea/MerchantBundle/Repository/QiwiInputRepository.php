<?php namespace Primearea\MerchantBundle\Repository;

use Well\DBBundle\DB\DefaultDbClientTrait;

class QiwiInputRepository
{
    use DefaultDbClientTrait;

    /**
     * @return int|null
     */
    public function getLastTxnId(): ?int
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT `value`
FROM setting
WHERE id = 17
SQL
            )
            ->execute();

        $nextTxnId = (int) $statement->fetchColumn();
        $nextTxnId = $nextTxnId ?: null;

        return $nextTxnId;
    }
}
