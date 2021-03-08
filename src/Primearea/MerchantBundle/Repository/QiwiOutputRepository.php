<?php namespace Primearea\MerchantBundle\Repository;

use Primearea\MerchantBundle\Dto\QiwiOutput;
use Well\DBBundle\DB\DefaultDbClientTrait;

class QiwiOutputRepository
{
    use DefaultDbClientTrait;

    public function createUpdate(QiwiOutput $qiwiOutput)
    {
        $this->defaultDbClient->prepare(<<<SQL
INSERT INTO qiwi_output
  (`cashout_id`, `amount`, `fee`, `purse`)
VALUES
  (:cashout_id, :amount, :fee, :purse)
ON DUPLICATE KEY UPDATE
  `id` = LAST_INSERT_ID(id),
  `amount` = VALUES(`amount`),
  `fee` = VALUES(`fee`),
  `purse` = VALUES(`purse`)
SQL
        )
            ->execute([
                'cashout_id' => $qiwiOutput->cashoutId,
                'amount' => $qiwiOutput->amount,
                'fee' => $qiwiOutput->fee,
                'purse' => $qiwiOutput->purse,
            ]);

        $qiwiOutput->id = $this->defaultDbClient->lastInsertId();
    }

    public function getSettings(): array
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT `value`
FROM setting
WHERE id IN (18,19)
ORDER BY id
SQL
            )
            ->execute();

        $enabled = (bool) $statement->fetchColumn();
        $fee = $statement->fetchColumn();

        return ['enabled' => $enabled, 'fee' => $fee];
    }
}
