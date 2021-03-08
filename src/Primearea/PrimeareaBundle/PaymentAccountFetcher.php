<?php namespace Primearea\PrimeareaBundle;

use Exception;
use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Primearea\PrimeareaBundle\Repository\PaymentAccountRepository;

class PaymentAccountFetcher
{
    const PAYMENT_SYSTEM_YANDEX_ID = 1;
    const PAYMENT_SYSTEM_YANDEX_CARD_ID = 2;
    const PAYMENT_SYSTEM_QIWI_ID = 3;

    const ENABLED_FOR_SHOP = 'shop';
    const ENABLED_FOR_MERCHANT = 'merchant';
    const ENABLED_FOR_WITHDRAW = 'withdraw';

    private $paymentAccountRepository;

    public function __construct(PaymentAccountRepository $paymentAccountRepository)
    {
        $this->paymentAccountRepository = $paymentAccountRepository;
    }

    public function fetchOne(int $paymentSystemId, string $enabledFor): PaymentAccountDto
    {
        $paymentAccounts = $this->paymentAccountRepository->getAllBySystemIdEnabledFor($paymentSystemId, $enabledFor);

        if (count($paymentAccounts) === 0) {
            throw new Exception('Платежное направление недоступно');
        }

        $keys = array_keys($paymentAccounts);
        $weights = array_map(function (PaymentAccountDto $a) {
            return $a->weight;
        }, $paymentAccounts);

        $key = $this->weightedRandom($keys, $weights);
        $paymentAccount = $paymentAccounts[$key];

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
