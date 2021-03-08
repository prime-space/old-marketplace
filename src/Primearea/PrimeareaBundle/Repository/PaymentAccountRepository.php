<?php namespace Primearea\PrimeareaBundle\Repository;

use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Well\DBBundle\DB\DefaultDbClientTrait;
use Well\DBBundle\Exception\EntryNotFoundException;

class PaymentAccountRepository
{
    const PAYMENT_SYSTEM_YANDEX_ID = 1;
    const PAYMENT_SYSTEM_YANDEX_CARD_ID = 2;
    const PAYMENT_SYSTEM_QIWI_ID = 3;

    use DefaultDbClientTrait;

    public function insertOnDuplicateUpdate(PaymentAccountDto $account)
    {
        $this
            ->defaultDbClient
            ->prepare(<<<SQL
INSERT INTO payment_account
    (id, paymentSystemId, name, config, weight, enabled)
VALUES
    (:id, :paymentSystemId, :name, :config, :weight, :enabled)
ON DUPLICATE KEY UPDATE
    paymentSystemId = VALUES(paymentSystemId),
    name = VALUES(name),
    config = VALUES(config),
    weight = VALUES(weight),
    enabled = VALUES(enabled)
SQL
            )
            ->execute([
                'id' => $account->id,
                'paymentSystemId' => $account->paymentSystemId,
                'name' => $account->name,
                'config' => json_encode($account->config),
                'weight' => $account->weight,
                'enabled' => implode(',', $account->enabled),
            ]);
    }

    /**
     * @param int $paymentSystemId
     * @return PaymentAccountDto[]
     */
    public function getAllBySystemIdIndexedById(int $paymentSystemId): array
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT pa.id, pa.paymentSystemId, pa.name, pa.config, pa.weight, pa.enabled
FROM payment_account pa
WHERE paymentSystemId = :paymentSystemId
SQL
            )
            ->execute([
                'paymentSystemId' => $paymentSystemId,
            ]);

        $items = [];
        try {
            while ($item = $statement->fetchObject(PaymentAccountDto::class)) {
                $items[$item->id] = $item;
            }
        } catch (EntryNotFoundException $e) {
        }

        return $items;
    }

    public function getById(int $paymentAccountId): ?PaymentAccountDto
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT pa.id, pa.paymentSystemId, pa.name, pa.config, pa.weight, pa.enabled
FROM payment_account pa
WHERE id = :paymentAccountId
SQL
            )
            ->execute([
                'paymentAccountId' => $paymentAccountId,
            ]);

        try {
            /** @var PaymentAccountDto $item */
            $item = $statement->fetchObject(PaymentAccountDto::class);

            return $item;
        } catch (EntryNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param int $paymentSystemId
     * @param string $enabledFor
     * @return PaymentAccountDto[]
     */
    public function getAllBySystemIdEnabledFor(int $paymentSystemId, string $enabledFor): array
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT pa.id, pa.paymentSystemId, pa.name, pa.config, pa.weight, pa.enabled
FROM payment_account pa
WHERE
    paymentSystemId = :paymentSystemId
    AND FIND_IN_SET(:enabledFor, enabled) > 0
SQL
            )
            ->execute([
                'paymentSystemId' => $paymentSystemId,
                'enabledFor' => $enabledFor,
            ]);

        $items = [];
        try {
            while ($item = $statement->fetchObject(PaymentAccountDto::class)) {
                $items[] = $item;
            }
        } catch (EntryNotFoundException $e) {
        }

        return $items;
    }

    public function getUsingStat(): array
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT 
    payment_account_id,
    SUM(`day`) as `day`,
    SUM(hours) as hours,
    SUM(minutes) as minutes
FROM (
    SELECT
        o.payment_account_id,
         SUM(1) as `day`,
        SUM(IF(o.date > ADDDATE(NOW(), INTERVAL -2 HOUR), 1, 0)) as hours,
        SUM(IF(o.date > ADDDATE(NOW(), INTERVAL -15 MINUTE), 1, 0)) as minutes
    FROM `order` o
    WHERE
        o.date > ADDDATE(NOW(), INTERVAL -1 DAY)
        AND o.`status` IN ('sended', 'review')
        AND o.payment_account_id <> 0
    GROUP BY o.payment_account_id
    
    UNION ALL
    
    SELECT
        p.payment_account_id,
         SUM(1) as `day`,
        SUM(IF(p.ts > ADDDATE(NOW(), INTERVAL -2 HOUR), 1, 0)) as hours,
        SUM(IF(p.ts > ADDDATE(NOW(), INTERVAL -15 MINUTE), 1, 0)) as minutes
    FROM mpayments p
    WHERE
        p.ts > ADDDATE(NOW(), INTERVAL -1 DAY)
        AND p.`status` = 'success'
        AND p.payment_account_id <> 0
    GROUP BY p.payment_account_id
    
    UNION ALL
    
    SELECT
        a.payment_account_id,
         SUM(1) as `day`,
        SUM(IF(a.datetime > ADDDATE(NOW(), INTERVAL -2 HOUR), 1, 0)) as hours,
        SUM(IF(a.datetime > ADDDATE(NOW(), INTERVAL -15 MINUTE), 1, 0)) as minutes
    FROM addmoney a
    WHERE
        a.datetime > ADDDATE(NOW(), INTERVAL -1 DAY)
        AND a.`status` = 'Пополнено'
        AND a.payment_account_id <> 0
    GROUP BY a.payment_account_id
) q
GROUP BY payment_account_id
SQL
            )
            ->execute();

        $items = $statement->fetchArrays();

        return $items;
    }

    public function getTurnover()
    {
        $statement = $this
            ->defaultDbClient
            ->prepare(<<<SQL
SELECT 
    payment_account_id,
    SUM(currentMounth) as currentMounth,
    SUM(lastMonth) as lastMonth
FROM (
    SELECT
        o.payment_account_id,
        SUM(IF(o.date > DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), o.totalBuyer, 0)) as currentMounth,
        SUM(IF(o.date < DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), o.totalBuyer, 0)) as lastMonth
    FROM `order` o
    WHERE
        o.date > ADDDATE(DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), INTERVAL -1 MONTH)
        AND o.`status` IN ('sended', 'review')
        AND o.payment_account_id <> 0
    GROUP BY o.payment_account_id
    
    UNION ALL
    
    SELECT
        p.payment_account_id,
        SUM(IF(p.ts > DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), p.amountPay, 0)) as currentMounth,
        SUM(IF(p.ts < DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), p.amountPay, 0)) as lastMonth
    FROM mpayments p
    WHERE
        p.ts > ADDDATE(DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), INTERVAL -1 MONTH)
        AND p.`status` = 'success'
        AND p.payment_account_id <> 0
    GROUP BY p.payment_account_id
    
    UNION ALL
    
    SELECT
        a.payment_account_id,
        SUM(IF(a.datetime > DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), a.money, 0)) as currentMounth,
        SUM(IF(a.datetime < DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), a.money, 0)) as lastMonth
    FROM addmoney a
    WHERE
        a.datetime > ADDDATE(DATE_FORMAT(NOW(), "%Y-%m-01 00:00:00"), INTERVAL -1 MONTH)
        AND a.`status` = 'Пополнено'
        AND a.payment_account_id <> 0
    GROUP BY a.payment_account_id
) q
GROUP BY payment_account_id
SQL
            )
            ->execute();

        $items = $statement->fetchArrays();

        return $items;
    }
}
