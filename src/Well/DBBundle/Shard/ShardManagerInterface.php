<?php namespace Well\DBBundle\Shard;

use Well\DBBundle\DB\Client;
use Well\DBBundle\Exception\ShardNotFoundException;

/**
 * Shard manager interface
 */
interface ShardManagerInterface
{
    /**
     * Getter for shard type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Getter for database client by external ID
     *
     * @param int $external_id External ID
     * @return Client DB Client
     */
    public function getClientByExternalId(int $external_id): Client;

    /**
     * Getter for database client by shard ID
     *
     * @param int $shard_id Shard ID
     * @return Client DB Client
     * @throws ShardNotFoundException
     */
    public function getClientByShardId(int $shard_id): Client;

    /**
     * Getter for active shard ID
     *
     * @return int
     * @throws ShardNotFoundException
     */
    public function getActiveShardId(): int;
}
