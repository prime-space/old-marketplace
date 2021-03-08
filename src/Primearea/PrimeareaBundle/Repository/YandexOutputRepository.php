<?php namespace Primearea\PrimeareaBundle\Repository;

use Primearea\PrimeareaBundle\Dto\YandexOutputDto;
use Well\DBBundle\DB\DefaultDbClientTrait;

class YandexOutputRepository
{
    use DefaultDbClientTrait;

    public function createUpdate(YandexOutputDto $yandexOutput)
    {
        $this->defaultDbClient->prepare(<<<SQL
INSERT INTO yandex_output
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
                'cashout_id' => $yandexOutput->cashoutId,
                'amount' => $yandexOutput->amount,
                'fee' => $yandexOutput->fee,
                'purse' => $yandexOutput->purse,
            ]);

        $yandexOutput->id = $this->defaultDbClient->lastInsertId();
    }

    public function getSettings(): array
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT `value`
FROM setting
WHERE id IN (15,16)
ORDER BY id
SQL
            )
            ->execute();

        $enabled = (bool) $statement->fetchColumn();
        $fee = $statement->fetchColumn();

        return ['enabled' => $enabled, 'fee' => $fee];
    }
}
