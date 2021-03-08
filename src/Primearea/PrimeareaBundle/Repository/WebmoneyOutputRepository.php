<?php namespace Primearea\PrimeareaBundle\Repository;

use Well\DBBundle\DB\DefaultDbClientTrait;

class WebmoneyOutputRepository
{
    use DefaultDbClientTrait;

    public function getSettings(): array
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT `value`
FROM setting
WHERE id IN (7,8)
ORDER BY id
SQL
            )
            ->execute();

        $fee = $statement->fetchColumn();
        $enabled = (bool) $statement->fetchColumn();

        return ['enabled' => $enabled, 'fee' => $fee];
    }
}
