<?php namespace Primearea\PrimeareaBundle\Command;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use Primearea\MerchantBundle\Dto\QiwiInput;
use Primearea\MerchantBundle\Dto\Transaction;
use Primearea\MerchantBundle\Exception\PaymentNotFoundException;
use Primearea\MerchantBundle\Repository\TransactionRepository;
use Primearea\MerchantBundle\Store\QiwiInputStore;
use Primearea\OrderBundle\Exception\OrderNotFoundException;
use Primearea\PrimeareaBundle\Aggregator\QiwiAggregator;
use Primearea\PrimeareaBundle\Dto\PaymentAccountDto;
use Primearea\PrimeareaBundle\Exception\AggregatorCheckingException;
use Primearea\PrimeareaBundle\Exception\PortCannotExecException;
use Primearea\PrimeareaBundle\Exception\TransactionNotExpectedToApply;
use Primearea\PrimeareaBundle\Repository\PaymentAccountRepository;
use Primearea\PrimeareaBundle\Transaction\ExecutorInterface;
use Primearea\PrimeareaBundle\Transaction\ExecutorProvider;
use Primearea\PrimeareaBundle\Transaction\TransactionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Well\DBBundle\DB\Client as DbClient;
use Well\DBBundle\Exception\ExecuteException;

//@TODO CODE DUPLICATE FROM \Primearea\MerchantBundle\Command\QiwiTransactionsFetchDaemonCommand
class QiwiTransactionsReexec extends Command
{
    private const QIWI_TRANSACTION_STATUS_SUCCESS = 'SUCCESS';
    private const QIWI_TRANSACTION_STATUS_ERROR = 'ERROR';

    private $guzzleClient;
    private $executorProvider;
    private $qiwiAggregator;
    private $defaultDbClient;
    private $transactionRepository;
    private $paymentAccountRepository;
    private $qiwiInputStore;

    public function __construct(
        Client $guzzleClient,
        ExecutorProvider $executorProvider,
        QiwiInputStore $qiwiInputStore,
        QiwiAggregator $qiwiAggregator,
        DbClient $defaultDbClient,
        TransactionRepository $transactionRepository,
        PaymentAccountRepository $paymentAccountRepository
    ) {
        parent::__construct();

        $this->guzzleClient = $guzzleClient;
        $this->executorProvider = $executorProvider;
        $this->qiwiAggregator = $qiwiAggregator;
        $this->defaultDbClient = $defaultDbClient;
        $this->transactionRepository = $transactionRepository;
        $this->paymentAccountRepository = $paymentAccountRepository;
        $this->qiwiInputStore = $qiwiInputStore;
    }

    protected function configure()
    {
        $this
            ->setName('primearea:qiwi-transactions-reexec')
            ->addArgument('paymentAccountId', InputArgument::REQUIRED)
            ->addArgument('interval', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paymentAccountId = $input->getArgument('paymentAccountId');
        $interval = $input->getArgument('interval');

        $paymentAccount = $this->paymentAccountRepository->getById($paymentAccountId);
        if (null === $paymentAccount) {
            $output->writeln('Account not found');

            return;
        }
        if ($paymentAccount->paymentSystemId !== PaymentAccountRepository::PAYMENT_SYSTEM_QIWI_ID) {
            $output->writeln("It's not qiwi account");

            return;
        }

        $startDate = new DateTime("-$interval");

        $transactions = $this->fetchQiwiTransactions($paymentAccount, $startDate, $output);
        foreach ($transactions as $transaction) {
            $this->handleTransaction($transaction, $output);
        }
    }

    private function fetchQiwiTransactions(
        PaymentAccountDto $paymentAccount,
        DateTime $startDate,
        OutputInterface $output
    ) {
        $transactions = [];
        $nextTxnId = null;
        $nextTxnDate = null;
        $endDate = new DateTime();
        while (1) {
            $result = $this->request($output, $paymentAccount, $startDate, $endDate, $nextTxnId, $nextTxnDate);
            $nextTxnId = $result['nextTxnId'];
            $nextTxnDate = $result['nextTxnDate'];
            $transactions = array_merge($transactions, $result['data']);
            $output->writeln(count($transactions));
            if (empty($nextTxnId)) {
                break;
            }
            sleep(5);
        }

        return $transactions;
    }

    private function request(
        OutputInterface $output,
        PaymentAccountDto $paymentAccount,
        DateTime $startDate,
        DateTime $endDate,
        $nextTxnId = null,
        $nextTxnDate = null
    ) {
        $params = [
            'rows' => 50,
            'operation' => 'IN',
            'startDate' => $startDate->format(DateTime::W3C),
            'endDate' => $endDate->format(DateTime::W3C),
        ];
        if (null !== $nextTxnId) {
            $params['nextTxnId'] = $nextTxnId;
            $params['nextTxnDate'] = $nextTxnDate;
        }
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
        $result = json_decode($request->getBody()->getContents(), true);

        return $result;
    }
    private function handleTransaction(array $qiwiTransaction, OutputInterface $output)
    {
        $output->writeln("Handle {$qiwiTransaction['comment']}");
        $transactionIdRegexp = '/\(ID:('.implode('|', array_keys(Transaction::TYPES)).')(\d+)\)/';
        if (1 === preg_match($transactionIdRegexp, $qiwiTransaction['comment'], $matches)) {
            try {
                $transactionId = (int)$matches[2];
                $transactionType = Transaction::TYPES[$matches[1]];
                $executor = $this->executorProvider->getExecutor($transactionType);
                $transaction = $this->getTransactionForApply($executor, $transactionId);
                //@TODO
                try {
                    $qiwiInputId = $this->saveTransaction($qiwiTransaction, $transactionType, $transactionId);
                } catch (ExecuteException $e) {
                    throw new Exception('Cannot create qiwi_input');
                }
                $amount = $qiwiTransaction['sum']['amount'];
                $currency = $qiwiTransaction['sum']['currency'];
                $this->qiwiAggregator->check($transaction, $amount, $currency);
                $output->writeln("Status: {$qiwiTransaction['status']}");
                if ($qiwiTransaction['status'] === self::QIWI_TRANSACTION_STATUS_SUCCESS) {
                    $executor->success($transactionId);
                } elseif ($qiwiTransaction['status'] === self::QIWI_TRANSACTION_STATUS_ERROR) {
                    $executor->fail($transactionId);
                }
                $this->qiwiInputStore->markAsHandled($qiwiInputId);
            } catch (Exception $e) {
                $output->writeln($e->getMessage());
            }
        } else {
            $output->writeln("Transaction not identified");
        }
    }
    private function saveTransaction(array $transaction, $transactionType = null, $transactionId = null)
    {
        $qiwiInput = QiwiInput::create($transaction, $transactionType, $transactionId);
        $qiwiInputId = $this->qiwiInputStore->create($qiwiInput);

        return $qiwiInputId;
    }
    /**
     * @throws TransactionNotExpectedToApply
     */
    private function getTransactionForApply(ExecutorInterface $executor, int $transactionId): TransactionInterface
    {
        $transaction = $executor->getTransaction($transactionId);

        if (!$executor->isNeedApply($transaction)) {
            throw new TransactionNotExpectedToApply('Executor isNeedApply');
        }

        return $transaction;
    }
}
