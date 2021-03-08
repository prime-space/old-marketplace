<?php namespace Primearea\PrimeareaBundle;

use GuzzleHttp;
use Well\DBBundle\DB\Client;
use Well\DBBundle\Exception\EntryNotFoundException;

class MessageBroker
{
    public const QUEUE_WITHDRAW_NAME = 'withdraw';
    public const QUEUE_EXEC_PAYMENT_NAME = 'exec_payment';

    /**
     * @var Client
     */
    private $queueDbClient;

    /**
     * @param Client $queueDbClient
     */
    public function __construct(Client $queueDbClient)
    {
        $this->queueDbClient = $queueDbClient;
    }

    /**
     * @return array|null
     */
    public function getMessage(string $queue): array
    {
        while (1) {
            try {
                $statement = $this
                    ->queueDbClient
                    ->prepare('CALL sp_get_message(:queue)')
                    ->execute(['queue' => $queue]);

                $data = GuzzleHttp\json_decode($statement->fetchColumn(), true);

                break;
            } catch (EntryNotFoundException $e) {
                continue;
            }
        }

        return $data;
    }

    public function createMessage(string $queue, array $data): void
    {
        $this->queueDbClient
            ->prepare('CALL sp_create_message(:queue, :message)')
            ->execute([
                'queue' => $queue,
                'message' => json_encode($data),
            ]);
    }
}
