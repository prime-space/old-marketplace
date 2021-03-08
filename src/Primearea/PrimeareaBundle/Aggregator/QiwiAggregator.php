<?php namespace Primearea\PrimeareaBundle\Aggregator;

use Exception;
use GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Primearea\MerchantBundle\Dto\QiwiOutput;
use Primearea\MerchantBundle\Repository\QiwiOutputRepository;
use Primearea\PrimeareaBundle\Dto\Cashout;
use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Primearea\PrimeareaBundle\Exception\AggregatorCheckingException;
use Primearea\PrimeareaBundle\Exception\WithdrawException;
use Primearea\PrimeareaBundle\PaymentAccountFetcher;
use Primearea\PrimeareaBundle\Repository\UserRepository;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;

class QiwiAggregator extends AggregatorAbstract
{
    private const CURRENCY_ID_RUB = 643;
    public const AGGREGATOR_NAME = 'qiwi';

    private $guzzleClient;
    private $qiwiOutputRepository;
    private $settings;

    /**
     * QiwiAggregator constructor.
     * @param UserRepository $userRepository
     * @param QiwiOutputRepository $qiwiOutputRepository
     * @param Client $guzzleClient
     * @param PaymentAccountFetcher $paymentAccountFetcher
     * @param LoggerInterface $logger
     */
    public function __construct(
        UserRepository $userRepository,
        QiwiOutputRepository $qiwiOutputRepository,
        Client $guzzleClient,
        PaymentAccountFetcher $paymentAccountFetcher,
        LoggerInterface $logger
    ) {
        parent::__construct($userRepository, $paymentAccountFetcher, $logger);
        $this->guzzleClient = $guzzleClient;
        $this->qiwiOutputRepository = $qiwiOutputRepository;
    }

    /**
     * @param TransactionInterface $transaction
     * @param $paidAmount
     * @param $currency
     * @throws AggregatorCheckingException
     */
    public function check(TransactionInterface $transaction, $paidAmount, $currency)
    {
        $transactionAmount = $transaction->getAmount();
        if (1 === bccomp($transactionAmount, $paidAmount, 2)) {
            throw new AggregatorCheckingException("Paid less. Transaction: $transactionAmount Paid: $paidAmount");
        }

        if ($currency !== self::CURRENCY_ID_RUB) {
            throw new AggregatorCheckingException('Currency not match');
        }
    }

    /** @inheritdoc */
    public function getPurse(Cashout $cashout): string
    {
        return parent::getPurseAbstract($cashout, 'qiwi_purse');
    }

    public function isWithdrawEnabled(): bool
    {
        $this->settings = $this->qiwiOutputRepository->getSettings();

        return $this->settings['enabled'];
    }


    /** @inheritdoc */
    public function getPaymentAccount(): PaymentAccountDto
    {
        try {
            $paymentAccount = $this->paymentAccountFetcher->fetchOne(
                PaymentAccountFetcher::PAYMENT_SYSTEM_QIWI_ID,
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

    public function getPrimePayerMethodName(): string
    {
        return 'qiwi';
    }
}
