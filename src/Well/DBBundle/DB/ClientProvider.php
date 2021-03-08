<?php namespace Well\DBBundle\DB;

class ClientProvider
{
    /**
     * @var Client[]
     */
    private $clients = [];

    public function addClient(Client $client)
    {
        $this->clients[] = $client;
    }

    /**
     * @return Client[]
     */
    public function getClients(): array
    {
        return $this->clients;
    }
}
