<?php namespace Primearea\PrimeareaBundle\Repository;

use Primearea\PrimeareaBundle\Dto\User;
use Well\DBBundle\DB\DefaultDbClientTrait;
use Well\DBBundle\Exception\EntryNotFoundException;

class UserRepository
{
    use DefaultDbClientTrait;

    public function find(int $id): ?User
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT id, wmr, yandex_purse, qiwi_purse
FROM user
WHERE id = :id
SQL
            )
            ->execute(['id' => $id]);

        try {
            /** @var User $item */
            $item = $statement->fetchObject(User::class);

            return $item;
        } catch (EntryNotFoundException $e) {
            return null;
        }
    }
}
