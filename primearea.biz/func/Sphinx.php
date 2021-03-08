<?php

class Sphinx
{
    /** @var string DSN format */
    private static $dsn_format = 'mysql:host=%1$s;port=%2$s;charset=%3$s';

    /** @var PDO PDO object */
    private $pdo;

    /** @var string PDO DSN string */
    private $dsn;

    public function __construct(
        $host = '127.0.0.1',
        $port = 9306,
        $charset = 'utf8mb4'
    ) {
        $this->dsn = sprintf(self::$dsn_format, $host, $port, $charset);
    }

    public function connect()
    {
        if (null === $this->pdo) {
            $this->pdo = new PDO($this->dsn);
        }

        return $this;
    }

    public function getPDO()
    {
        if (null === $this->pdo) {
            $this->connect();
        };

        return $this->pdo;
    }

    public function request($query, $parameters = [])
    {
        $statement = $this->getPDO()->prepare($query);
        $statement->execute($parameters);

        return $statement;
    }
}
