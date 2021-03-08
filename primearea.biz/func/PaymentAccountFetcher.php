<?php

class PaymentAccountFetcher
{
    const PAYMENT_SYSTEM_YANDEX_ID = 1;
    const PAYMENT_SYSTEM_YANDEX_CARD_ID = 2;
    const PAYMENT_SYSTEM_QIWI_ID = 3;

    const ENABLED_FOR_SHOP = 'shop';
    const ENABLED_FOR_MERCHANT = 'merchant';
    const ENABLED_FOR_WITHDRAW = 'withdraw';

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function fetchOne($paymentSystemId, $enabledFor)
    {
        $paymentAccounts = $this->db->super_query("
            SELECT id, config, weight
            FROM payment_account
            WHERE
                paymentSystemId = $paymentSystemId
                AND FIND_IN_SET('$enabledFor', enabled) > 0
         ", true);

        if (count($paymentAccounts) === 0) {
            throw new Exception('Платежное направление недоступно');
        }

        $keys = array_keys($paymentAccounts);
        $weights = array_map(function ($a) {
            return $a['weight'];
        }, $paymentAccounts);

        $key = $this->weightedRandom($keys, $weights);
        $paymentAccount = $paymentAccounts[$key];
        $paymentAccount['config'] = json_decode($paymentAccount['config'], true);

        return $paymentAccount;
    }

    public function getById($paymentAccountId)
    {
        $paymentAccount = $this->db->super_query("
            SELECT id, config, weight
            FROM payment_account
            WHERE id = $paymentAccountId
         ");

        if (null === $paymentAccount) {
            throw new Exception('Payment account not found');
        }

        $paymentAccount['config'] = json_decode($paymentAccount['config'], true);

        return $paymentAccount;
    }

    private function weightedRandom($keys, $weights)
    {
        $total = array_sum($weights);
        $n = 0;

        $num = mt_rand(1, $total);
        foreach ($keys as $i => $value) {
            $n += $weights[$i];
            if ($n >= $num) {
                return $keys[$i];
            }
        }
    }
}
