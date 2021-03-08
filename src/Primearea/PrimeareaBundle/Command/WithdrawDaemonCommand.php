<?php namespace Primearea\PrimeareaBundle\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Primearea\PrimeareaBundle\Aggregator\AggregatorProvider;
use Primearea\PrimeareaBundle\Dto\Cashout;
use Primearea\PrimeareaBundle\Exception\WithdrawException;
use Primearea\PrimeareaBundle\Logger\LogExtraDataKeeper;
use Primearea\PrimeareaBundle\MessageBroker;
use Primearea\PrimeareaBundle\Repository\CashoutRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Well\DBBundle\DB\ClientProvider;

class WithdrawDaemonCommand extends Daemon
{
    private $messageBroker;
    private $cashoutRepository;
    private $guzzleClient;
    private $primePayerDomain;
    private $primePayerUserId;
    private $primePayerApiKey;
    private $aggregatorProvider;

    protected function configure()
    {
        $this->setName('primearea:withdraw-daemon');
    }
    public function __construct(
        MessageBroker $messageBroker,
        CashoutRepository $cashoutRepository,
        AggregatorProvider $aggregatorProvider,
        Client $guzzleClient,
        ClientProvider $dbClientProvider,
        LogExtraDataKeeper $logExtraDataKeeper,
        LoggerInterface $logger,
        string $primePayerDomain,
        string $primePayerUserId,
        string $primePayerApiKey
    ) {
        parent::__construct($dbClientProvider, $logExtraDataKeeper, $logger);
        $this->messageBroker = $messageBroker;
        $this->cashoutRepository = $cashoutRepository;
        $this->guzzleClient = $guzzleClient;
        $this->primePayerDomain = $primePayerDomain;
        $this->primePayerUserId = $primePayerUserId;
        $this->primePayerApiKey = $primePayerApiKey;
        $this->aggregatorProvider = $aggregatorProvider;
    }

    protected function do(SymfonyStyle $io)
    {
        $message = $this->messageBroker->getMessage(MessageBroker::QUEUE_WITHDRAW_NAME);
        $attempt = isset($message['try']) ? $message['try'] : 1;
        if ($attempt > 1) {
            sleep(5);
        }

        $cashout = $this->cashoutRepository->findCashout($message['id']);
        if (null === $cashout) {
            $this->logger->critical("Cashout #{$message['id']} not found");

            return;
        } elseif ($cashout->status !== Cashout::STATUS_NEW) {
            $this->logger->error(
                "Cashout #{$cashout->id} is not expected to sending (Status: '{$cashout->status}')"
            );

            return;
        }
        $aggregator = $this->aggregatorProvider->getAggregator($message['aggregator']);
        if ($aggregator->isWithdrawEnabled()) {
            list($debit, $feeDebit) = $aggregator->calculateWithdraw($cashout->amount, $aggregator->getPayoutFee());
            try {
                /*DEBUG*/$timeStart = microtime(true);
                $request = $this->guzzleClient->post(
                    "https://{$this->primePayerDomain}/api/{$this->primePayerUserId}/payout",
                    [
                        'timeout' => 15,
                        'connect_timeout' => 15,
                        'headers' => [
                            'Authorization' => "Bearer {$this->primePayerApiKey}",
                        ],
                        'form_params' => [
                            'id' => $cashout->id,
                            'receiver' => $aggregator->getPurse($cashout),
                            'method' => $aggregator->getPrimePayerMethodName(),
                            'accountId' => 4,
                            'amount' => $debit,
                        ]
                    ]
                );
                $result = json_decode($request->getBody()->getContents(), true);
                if (!isset($result['operationId'])) {
                    throw new WithdrawException('result cannot content operationId');
                }
                $cashout->status = Cashout::STATUS_PROCESS;
                $cashout->debit = $debit;
                $cashout->externalId = $result['operationId'];

                /*DEBUG*/$timeDuration = microtime(true) - $timeStart;

                $this->cashoutRepository->update($cashout);
                $this->logger->info("Cashout sended. Duration '$timeDuration'", ['cashoutId' => $cashout->id]);
            } catch (RequestException|WithdrawException $e) {
                if ($attempt < 3) {
                    $message['try'] = $attempt + 1;
                    $this->messageBroker->createMessage(MessageBroker::QUEUE_WITHDRAW_NAME, $message);
                    $this->logger->error(
                        'Cashout sending error. Retry',
                        ['cashoutId' => $cashout->id, 'message' => $e->getMessage()]
                    );
                } else {
                    $cashout->status = Cashout::STATUS_ERROR;
                    $this->cashoutRepository->update($cashout);
                    $this->logger->critical(
                        'Cashout sending error',
                        ['cashoutId' => $cashout->id, 'message' => $e->getMessage()]
                    );
                }
            }
        }
    }
}
