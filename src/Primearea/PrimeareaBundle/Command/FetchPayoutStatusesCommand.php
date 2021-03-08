<?php namespace Primearea\PrimeareaBundle\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Primearea\PrimeareaBundle\Dto\Cashout;
use Primearea\PrimeareaBundle\Exception\CannotRequestPayoutStatusesException;
use Primearea\PrimeareaBundle\Repository\CashoutRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchPayoutStatusesCommand extends Command
{
    const COMMAND_NAME = 'primearea:fetch-payout-statuses';
    const EXTERNAL_STATUS_ID_QUEUE = 1;
    const EXTERNAL_STATUS_ID_PROCESS = 2;
    const EXTERNAL_STATUS_ID_SUCCESS = 3;
    const EXTERNAL_STATUS_ID_FAIL = 4;

    private $guzzleClient;
    private $logger;
    private $primePayerDomain;
    private $primePayerUserId;
    private $primePayerApiKey;
    private $cashoutRepository;

    public function __construct(
        Client $guzzleClient,
        LoggerInterface $logger,
        string $primePayerDomain,
        string $primePayerUserId,
        string $primePayerApiKey,
        CashoutRepository $cashoutRepository
    ) {
        parent::__construct();

        $this->guzzleClient = $guzzleClient;
        $this->logger = $logger;
        $this->primePayerDomain = $primePayerDomain;
        $this->primePayerUserId = $primePayerUserId;
        $this->primePayerApiKey = $primePayerApiKey;
        $this->cashoutRepository = $cashoutRepository;
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Start command ' . self::COMMAND_NAME);
        $statuses = [
            self::EXTERNAL_STATUS_ID_SUCCESS => Cashout::STATUS_PERFORMED,
            self::EXTERNAL_STATUS_ID_FAIL => Cashout::STATUS_ERROR,
        ];
        $cashout = $this->cashoutRepository->findFirstExternalIdWithProcess();
        if (null === $cashout) {
            $this->logger->info('Unhandled cashout not found ' . self::COMMAND_NAME);

            return;
        }
        try {
            $fromOperationId = $cashout->externalId;
            while (1) {
                $operations = $this->requestPayoutStatuses($fromOperationId);
                $operationsNum = count($operations);
                $this->logger->info("Recieved $operationsNum operations" . self::COMMAND_NAME);
                foreach ($operations as $operation) {
                    $resultingExternalStatuses = [self::EXTERNAL_STATUS_ID_SUCCESS, self::EXTERNAL_STATUS_ID_FAIL];
                    if (in_array($operation['statusId'], $resultingExternalStatuses, true)) {
                        $cashout = $this->cashoutRepository->findByExternalId($operation['id']);
                        if (null !== $cashout && in_array($cashout->status, [Cashout::STATUS_NEW, Cashout::STATUS_PROCESS], true)) {
                            $cashout->status = $statuses[$operation['statusId']];
                            $this->cashoutRepository->update($cashout);
                        }
                    }
                }
                if ($operationsNum === 100) {
                    $fromOperationId = $operation['id'];
                } else {
                    break;
                }
            }
            $this->logger->info('Success ' . self::COMMAND_NAME);
        } catch (CannotRequestPayoutStatusesException $e) {
            $this->logger
                ->error('Error ' . self::COMMAND_NAME, ['message' => $e->getMessage()]);
        }
    }

    /** @throws CannotRequestPayoutStatusesException */
    private function requestPayoutStatuses($fromOperationId): array
    {
        try {
            $request = $this->guzzleClient->post(
                "https://{$this->primePayerDomain}/api/{$this->primePayerUserId}/payouts",
                [
                    'timeout' => 6,
                    'connect_timeout' => 6,
                    'headers' => [
                        'Authorization' => "Bearer {$this->primePayerApiKey}",
                    ],
                    'form_params' => [
                        'fromOperationId' => $fromOperationId,
                    ]
                ]
            );
            $result = json_decode($request->getBody()->getContents(), true);

            return $result['operations'];
        } catch (RequestException $e) {
            throw new CannotRequestPayoutStatusesException($e->getMessage());

        }

    }
}
