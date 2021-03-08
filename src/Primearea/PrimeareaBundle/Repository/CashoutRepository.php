<?php namespace Primearea\PrimeareaBundle\Repository;

use Primearea\PrimeareaBundle\Dto\Cashout;
use Well\DBBundle\DB\DefaultDbClientTrait;
use Well\DBBundle\Exception\EntryNotFoundException;

class CashoutRepository
{
    use DefaultDbClientTrait;

    public function findCashout(int $id): ?Cashout
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT id, userId, amount, debit, `status`, paymentAccountId, externalId
FROM cashout
WHERE id = :id
SQL
            )
            ->execute(['id' => $id]);

        try {
            /** @var Cashout $cashout */
            $cashout = $statement->fetchObject(Cashout::class);

            return $cashout;
        } catch (EntryNotFoundException $e) {
            return null;
        }
    }

    public function findByExternalId(int $externalId): ?Cashout
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT id, userId, amount, debit, `status`, paymentAccountId, externalId
FROM cashout
WHERE externalId = :externalId
SQL
            )
            ->execute(['externalId' => $externalId]);

        try {
            /** @var Cashout $cashout */
            $cashout = $statement->fetchObject(Cashout::class);

            return $cashout;
        } catch (EntryNotFoundException $e) {
            return null;
        }
    }

    public function findFirstExternalIdWithProcess(): ?Cashout
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT id, userId, amount, debit, `status`, paymentAccountId, externalId
FROM cashout
WHERE 
    `status` = 'process'
    AND externalId IS NOT NULL
ORDER BY id ASC
LIMIT 1
SQL
            )
            ->execute();

        try {
            /** @var Cashout $cashout */
            $cashout = $statement->fetchObject(Cashout::class);

            return $cashout;
        } catch (EntryNotFoundException $e) {
            return null;
        }
    }

    public function update(Cashout $cashout)
    {
        $this
            ->defaultDbClient
            ->prepare(<<<SQL
UPDATE cashout
SET
    debit = :debit,
    `status` = :status,
    externalId = :externalId
WHERE id = :id
SQL
            )
            ->execute([
                'id' => $cashout->id,
                'debit' => $cashout->debit,
                'status' => $cashout->status,
                'externalId' => $cashout->externalId,
            ]);
    }
}
