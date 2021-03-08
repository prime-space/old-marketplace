<?php namespace Primearea\PrimeareaBundle\Aggregator;

use Exception;
use Primearea\PrimeareaBundle\Dto\Cashout;
use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Primearea\PrimeareaBundle\PaymentAccountFetcher;
use Primearea\PrimeareaBundle\Repository\UserRepository;
use Primearea\PrimeareaBundle\Repository\WebmoneyOutputRepository;
use Primearea\PrimeareaBundle\Repository\YandexOutputRepository;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class WebmoneyAggregator extends AggregatorAbstract
{
    public const AGGREGATOR_NAME = 'wm';
    public $settings;
    private $webmoneyOutputRepository;

    public function __construct(
        UserRepository $userRepository,
        WebmoneyOutputRepository $webmoneyOutputRepository,
        PaymentAccountFetcher $paymentAccountFetcher,
        LoggerInterface $logger
    ) {
        parent::__construct($userRepository, $paymentAccountFetcher, $logger);
        $this->webmoneyOutputRepository = $webmoneyOutputRepository;
    }

    /** @inheritdoc */
    public function check(TransactionInterface $transaction, $paidAmount, $currency)
    {
        //@TODO
    }

    /** @inheritdoc */
    public function getPurse(Cashout $cashout): string
    {
        return parent::getPurseAbstract($cashout, 'wmr');
    }

    public function getPrimePayerMethodName(): string
    {
        return 'webmoney_r';
    }

    public function isWithdrawEnabled(): bool
    {
        $this->settings = $this->webmoneyOutputRepository->getSettings();

        return $this->settings['enabled'];
    }

    /** @inheritdoc */
    public function getPaymentAccount(): PaymentAccountDto
    {
        //@TODO
    }

    public function getPayoutFee()
    {
        if (!isset($this->settings['fee'])) {
            throw new RuntimeException('No settings');
        }

        return $this->settings['fee'];
    }
}
