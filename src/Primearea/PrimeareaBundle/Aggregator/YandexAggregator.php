<?php namespace Primearea\PrimeareaBundle\Aggregator;

use Exception;
use Primearea\PrimeareaBundle\Aggregator\Yandex\API;
use Primearea\PrimeareaBundle\Dto\Cashout;
use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Primearea\PrimeareaBundle\Dto\YandexOutputDto;
use Primearea\PrimeareaBundle\Exception\WithdrawException;
use Primearea\PrimeareaBundle\PaymentAccountFetcher;
use Primearea\PrimeareaBundle\Repository\UserRepository;
use Primearea\PrimeareaBundle\Repository\YandexOutputRepository;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class YandexAggregator extends AggregatorAbstract
{
    public const AGGREGATOR_NAME = 'yandex';
    public $settings;
    private $yandexOutputRepository;

    public function __construct(
        UserRepository $userRepository,
        YandexOutputRepository $yandexOutputRepository,
        PaymentAccountFetcher $paymentAccountFetcher,
        LoggerInterface $logger
    ) {
        parent::__construct($userRepository, $paymentAccountFetcher, $logger);
        $this->yandexOutputRepository = $yandexOutputRepository;
    }

    /**
     * @param TransactionInterface $transaction
     * @param $paidAmount
     * @param $currency
     */
    public function check(TransactionInterface $transaction, $paidAmount, $currency)
    {
        //@TODO
    }

    /** @inheritdoc */
    public function getPurse(Cashout $cashout): string
    {
        return parent::getPurseAbstract($cashout, 'yandex_purse');
    }

    public function getPrimePayerMethodName(): string
    {
        return 'yandex';
    }

    public function isWithdrawEnabled(): bool
    {
        $this->settings = $this->yandexOutputRepository->getSettings();

        return $this->settings['enabled'];
    }

    /** @inheritdoc */
    public function getPaymentAccount(): PaymentAccountDto
    {
        try {
            $paymentAccount = $this->paymentAccountFetcher->fetchOne(
                PaymentAccountFetcher::PAYMENT_SYSTEM_YANDEX_ID,
                PaymentAccountFetcher::ENABLED_FOR_WITHDRAW
            );

            return $paymentAccount;
        } catch (Exception $e) {
            throw new WithdrawException($e->getMessage());
        }
    }

    public function getPayoutFee()
    {
        if (!isset($this->settings['fee'])) {
            throw new RuntimeException('No settings');
        }

        return $this->settings['fee'];
    }
}
