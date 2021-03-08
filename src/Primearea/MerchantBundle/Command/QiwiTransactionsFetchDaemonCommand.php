<?php namespace Primearea\MerchantBundle\Command;

use Exception;
use GuzzleHttp;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Primearea\MerchantBundle\Dto\QiwiInput;
use Primearea\MerchantBundle\Dto\Transaction;
use Primearea\MerchantBundle\Repository\QiwiInputRepository;
use Primearea\MerchantBundle\Repository\TransactionRepository;
use Primearea\MerchantBundle\Store\QiwiInputStore;
use Primearea\OrderBundle\Exception\OrderNotFoundException;
use Primearea\MerchantBundle\Exception\PaymentNotFoundException;
use Primearea\PrimeareaBundle\Aggregator\QiwiAggregator;
use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Primearea\PrimeareaBundle\Exception\AggregatorCheckingException;
use Primearea\PrimeareaBundle\Exception\PortCannotExecException;
use Primearea\PrimeareaBundle\Exception\TransactionNotExpectedToApply;
use Primearea\PrimeareaBundle\Repository\PaymentAccountRepository;
use Primearea\PrimeareaBundle\Transaction\ExecutorInterface;
use Primearea\PrimeareaBundle\Transaction\ExecutorProvider;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;
use Well\DBBundle\DB\Client as DbClient;
use Wrep\Daemonizable\Command\EndlessCommand;

class QiwiTransactionsFetchDaemonCommand extends EndlessCommand
{
    private const QIWI_TRANSACTION_STATUS_SUCCESS = 'SUCCESS';
    private const QIWI_TRANSACTION_STATUS_ERROR = 'ERROR';
    private const MAX_TRANSACTION_NUM_PER_LIFETIME = 125;
    private const FETCHING_PORTION_SIZE = 25;

    /** @var LoggerInterface */
    private $logger;
    /**
     * @var Client
     */
    private $guzzleClient;
    /**
     * @var QiwiInputRepository
     */
    private $qiwiInputRepository;
    /**
     * @var QiwiInputStore
     */
    private $qiwiInputStore;
    /**
     * @var ExecutorProvider
     */
    private $executorProvider;
    /**
     * @var QiwiAggregator
     */
    private $qiwiAggregator;
    /**
     * @var DbClient
     */
    private $defaultDbClient;
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;
    /**
     * @var int[]
     */
    private $notIdentifiedTransactionTxnIds = [];
    /**
     * @var int[]
     */
    private $requestExceptionCounter = 0;
    /**
     * @var PaymentAccountRepository
     */
    private $paymentAccountRepository;

    /**
     * @param Client $guzzleClient
     * @param QiwiInputRepository $qiwiInputRepository
     * @param QiwiInputStore $qiwiInputStore
     * @param ExecutorProvider $executorProvider
     * @param QiwiAggregator $qiwiAggregator
     * @param DbClient $defaultDbClient
     * @param TransactionRepository $transactionRepository
     * @param PaymentAccountRepository $paymentAccountRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Client $guzzleClient,
        QiwiInputRepository $qiwiInputRepository,
        QiwiInputStore $qiwiInputStore,
        ExecutorProvider $executorProvider,
        QiwiAggregator $qiwiAggregator,
        DbClient $defaultDbClient,
        TransactionRepository $transactionRepository,
        PaymentAccountRepository $paymentAccountRepository,
        LoggerInterface $logger
    ) {
        parent::__construct();

        $this->logger = $logger;
        $this->guzzleClient = $guzzleClient;
        $this->qiwiInputRepository = $qiwiInputRepository;
        $this->qiwiInputStore = $qiwiInputStore;
        $this->executorProvider = $executorProvider;
        $this->qiwiAggregator = $qiwiAggregator;
        $this->defaultDbClient = $defaultDbClient;
        $this->transactionRepository = $transactionRepository;
        $this->paymentAccountRepository = $paymentAccountRepository;
    }

    protected function configure()
    {
        $this->setName('primearea:merchant:fetch-qiwi-transactions-daemon');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->debug('Start fetch-qiwi-transactions');
        $lockHandler = new LockHandler('primearea_merchant_fetch_qiwi_transactions.lock');
        if (!$lockHandler->lock()) {
            $this->logger->critical('Sorry, cannot lock file');

            return;
        }

        try {
            $paymentAccounts = $this->paymentAccountRepository->getAllBySystemIdIndexedById(
                PaymentAccountRepository::PAYMENT_SYSTEM_QIWI_ID
            );
            $transactions = $this->transactionRepository->getLastTransactions();
            $transactionsNum = count($transactions);
            $transactionsByPaymentAccountsCounter = [];
            foreach ($transactions as $transaction) {
                if (!empty($transaction->paymentAccountId)) {
                    if (!isset($transactionsByPaymentAccountsCounter[$transaction->paymentAccountId])) {
                        $transactionsByPaymentAccountsCounter[$transaction->paymentAccountId] = [
                            'num' => 0,
                            'unhandledNum' => 0,
                        ];
                    }
                    $transactionsByPaymentAccountsCounter[$transaction->paymentAccountId]['num']++;
                    if (!$transaction->received) {
                        $transactionsByPaymentAccountsCounter[$transaction->paymentAccountId]['unhandledNum']++;
                    }
                }
            }
            $this->logger->info(
                'Fetch transactions for last ' . Transaction::LIFETIME_MINUTES . ' minutes from DB:',
                $transactionsByPaymentAccountsCounter
            );

            if ($transactionsNum > self::MAX_TRANSACTION_NUM_PER_LIFETIME) {
                $this->logger->critical(
                    'Excess of transaction num',
                    ['num' => $transactionsNum, 'lifetime' => self::MAX_TRANSACTION_NUM_PER_LIFETIME]
                );
            }

            foreach ($transactionsByPaymentAccountsCounter as $paymentAccountId => $transactionsByPayAccountCounter) {
                if ($transactionsByPayAccountCounter['unhandledNum'] > 0) {
                    $paymentAccount = $paymentAccounts[$paymentAccountId];
                    $qiwiTransactions = $this->fetchQiwiTransactions(
                        $paymentAccount,
                        $transactionsByPayAccountCounter['num']
                    );
                    foreach ($qiwiTransactions as $qiwiTransaction) {
                        $this->handleTransaction($qiwiTransaction, $transactions);
                    }
                }
            }
            $this->requestExceptionCounter = 0;
        } catch (RequestException $e) {
            if (++$this->requestExceptionCounter === 10) {
                $this->logger->critical($e->getMessage());
                $this->requestExceptionCounter = 0;
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->defaultDbClient->close();
        }

        $this->logger->debug('Done fetch-qiwi-transactions');
    }

    private function request(PaymentAccountDto $paymentAccount, $rows, $nextTxnId = null, $nextTxnDate = null)
    {
        $params = [
            'rows' => $rows,
            'operation' => 'IN'
        ];
        if (null !== $nextTxnId) {
            $params['nextTxnId'] = $nextTxnId;
            $params['nextTxnDate'] = $nextTxnDate;
        }
        $this->logger->debug('Request with params', $params);
        $query = http_build_query($params);
        $request = $this->guzzleClient->request(
            'GET',
            "https://edge.qiwi.com/payment-history/v1/persons/{$paymentAccount->config['account']}/payments?$query",
            [
                'timeout' => 3,
                'connect_timeout' => 3,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$paymentAccount->config['token']}",
                ]
            ]
        );
        $result = GuzzleHttp\json_decode($request->getBody()->getContents(), true);
        $rowsNumGot = count($result['data']);
        $this->logger->debug("Got rows num: $rowsNumGot");

        return $result;
    }

    /**
     * @param PaymentAccountDto $paymentAccount
     * @param int $num Number of transactions for fetching
     * @return array
     */
    private function fetchQiwiTransactions(PaymentAccountDto $paymentAccount, int $num)
    {
        $iterationsNum = (int)ceil($num / self::FETCHING_PORTION_SIZE);

        $transactions = [];
        $nextTxnId = null;
        $nextTxnDate = null;
        for ($i = 1; $i <= $iterationsNum; $i++) {
            $portionSize = $i === $iterationsNum
                ? $num - ($i - 1) * self::FETCHING_PORTION_SIZE
                : self::FETCHING_PORTION_SIZE;
            $result = $this->request($paymentAccount, $portionSize, $nextTxnId, $nextTxnDate);
            $nextTxnId = $result['nextTxnId'];
            $nextTxnDate = $result['nextTxnDate'];
            if (empty($nextTxnId)) {
                break;
            }
            $transactions = array_merge($transactions, $result['data']);
        }

        $this->logger->debug('Total got rows num: ' . count($transactions));

        return $transactions;
    }

    /**
     * @param array $qiwiTransaction
     * @param Transaction[] $lastTransactions
     */
    private function handleTransaction(array $qiwiTransaction, array $lastTransactions) {
        $this->logger->debug("Handling transaction", ['txnId' => $qiwiTransaction['txnId']]);
        $transactionIdRegexp = '/\(ID:('.implode('|', array_keys(Transaction::TYPES)).')(\d+)\)/';
        if (1 === preg_match($transactionIdRegexp, $qiwiTransaction['comment'], $matches)) {
            try {
                $transactionId = (int)$matches[2];
                $transactionType = Transaction::TYPES[$matches[1]];
                $executor = $this->executorProvider->getExecutor($transactionType);
                $this->logger->debug(
                    'Transaction identified',
                    ['id' => $matches[0], 'executor' => get_class($executor)]
                );
                $transaction = $this->getTransactionForApply(
                    $executor,
                    $transactionId,
                    $transactionType,
                    $lastTransactions
                );
                $qiwiInputId = $this->saveTransaction($qiwiTransaction, $transactionType, $transactionId);
                $amount = $qiwiTransaction['sum']['amount'];
                $currency = $qiwiTransaction['sum']['currency'];
                $this->qiwiAggregator->check($transaction, $amount, $currency);
                if ($qiwiTransaction['status'] === self::QIWI_TRANSACTION_STATUS_SUCCESS) {
                    $executor->success($transactionId);
                } elseif ($qiwiTransaction['status'] === self::QIWI_TRANSACTION_STATUS_ERROR) {
                    $executor->fail($transactionId);
                } else {
                    $this->logger->critical("Status: {$qiwiTransaction['status']}. Skip executing");
                }
                $this->qiwiInputStore->markAsHandled($qiwiInputId);
            } catch (PaymentNotFoundException
            |OrderNotFoundException
            |AggregatorCheckingException
            |PortCannotExecException $e
            ) {
                $this->logger->error($e->getMessage(), ['txnId' => $qiwiTransaction['txnId']]);
            } catch (TransactionNotExpectedToApply $e) {
                $this->logger->debug($e->getMessage(), ['txnId' => $qiwiTransaction['txnId']]);
            }
        } else {
            if (in_array($qiwiTransaction['txnId'], $this->notIdentifiedTransactionTxnIds, true)) {
                $this->logger->debug('Not identified repeatedly', ['txnId' => $qiwiTransaction['txnId']]);
            } else {
                $this->notIdentifiedTransactionTxnIds[] = $qiwiTransaction['txnId'];
                $this->notIdentifiedTransactionTxnIds = array_splice($this->notIdentifiedTransactionTxnIds, -1000);
                $this->logger->error('Transaction not identified', ['txnId' => $qiwiTransaction['txnId']]);
            }
        }
    }

    private function saveTransaction(array $transaction, $transactionType = null, $transactionId = null)
    {
        $qiwiInput = QiwiInput::create($transaction, $transactionType, $transactionId);
        $qiwiInputId = $this->qiwiInputStore->create($qiwiInput);

        return $qiwiInputId;
    }

    /**
     * @param ExecutorInterface $executor
     * @param int $transactionId
     * @param string $transactionType
     * @param Transaction[] $lastTransactions
     *
     * @return TransactionInterface
     *
     * @throws TransactionNotExpectedToApply
     */
    private function getTransactionForApply(
        ExecutorInterface $executor,
        int $transactionId,
        string $transactionType,
        array $lastTransactions
    ): TransactionInterface {
        $isExpectedToApply = false;
        foreach ($lastTransactions as $lastTransaction) {
            $matchedTransactionId = $lastTransaction->id === $transactionId;
            $matchedTransactionType = $lastTransaction->type === $transactionType;
            if ($matchedTransactionId && $matchedTransactionType && !$lastTransaction->received) {
                $isExpectedToApply = true;
            }
        }
        if (!$isExpectedToApply) {
            throw new TransactionNotExpectedToApply('LastTransactions');
        }

        $transaction = $executor->getTransaction($transactionId);

        if (!$executor->isNeedApply($transaction)) {
            throw new TransactionNotExpectedToApply('Executor isNeedApply');
        }

        return $transaction;
    }
}
