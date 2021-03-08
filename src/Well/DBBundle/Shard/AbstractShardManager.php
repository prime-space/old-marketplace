<?php namespace Well\DBBundle\Shard;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Well\DBBundle\DB\Client;
use Well\DBBundle\Exception\ShardNotFoundException;

/**
 * Abstract shard manager
 */
abstract class AbstractShardManager implements ShardManagerInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var LoggerInterface Psr logger */
    private $logger;

    /** @var Client[] Cached clients by external ID */
    private $cache;

    /** @var Client[] Clients by shard ID */
    private $clients;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger Psr logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientByExternalId(int $external_id): Client
    {
        if (isset($this->cache[$external_id])) {
            return $this->cache[$external_id];
        }

        $shard_id = $this->getShardIdByExternalId($external_id);
        if (isset($this->clients[$shard_id])) {
            return $this->clients[$shard_id];
        }

        $client = $this->getClientByShardId($shard_id);
        $this->cache[$external_id] = $client;

        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getClientByShardId(int $shard_id): Client
    {
        try {
            if (!isset($this->clients[$shard_id])) {
                $this->clients[$shard_id] = $this
                    ->container
                    ->get(sprintf(
                        'well.db.shard.%1$s.%2$s',
                        $this->getType(),
                        $shard_id
                    ));
            }
        } catch (Exception $exception) {
            $this->logger->error('Invalid shard configuration.', [
                'code'     => $exception->getCode(),
                'message'  => $exception->getMessage(),
                'shard_id' => $shard_id,
            ]);

            throw new ShardNotFoundException();
        }

        return $this->clients[$shard_id];
    }

    /**
     * Getter for shard ID by external ID
     *
     * @param int $external_id External ID
     * @return int
     */
    protected abstract function getShardIdByExternalId(int $external_id): int;
}
