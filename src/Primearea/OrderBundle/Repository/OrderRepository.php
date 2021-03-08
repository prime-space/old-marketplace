<?php namespace Primearea\OrderBundle\Repository;

use Primearea\OrderBundle\Dto\Order;
use Well\DBBundle\DB\DefaultDbClientTrait;
use Well\DBBundle\Exception\EntryNotFoundException;

class OrderRepository
{
    use DefaultDbClientTrait;

    public function findOrder(int $id): ?Order
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT id, `status`, totalBuyer
FROM `order`
WHERE id = :id
SQL
            )
            ->execute(['id' => $id]);

        try {
            /** @var Order $order */
            $order = $statement->fetchObject(Order::class);

            return $order;
        } catch (EntryNotFoundException $e) {
            return null;
        }
    }
}
