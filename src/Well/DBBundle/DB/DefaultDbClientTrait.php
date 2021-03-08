<?php namespace Well\DBBundle\DB;

trait DefaultDbClientTrait
{
    /** @var Client Default DB client */
    private $defaultDbClient;

    /**
     * @param Client $defaultDbClient
     */
    public function __construct(Client $defaultDbClient)
    {
        $this->defaultDbClient = $defaultDbClient;
    }
}
