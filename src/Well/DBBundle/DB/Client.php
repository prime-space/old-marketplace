<?php namespace Well\DBBundle\DB;

use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Well\DBBundle\Exception\ConnectionException;
use Well\DBBundle\Exception\DBException;
use Well\DBBundle\Exception\ExecuteException;
use Well\DBBundle\Exception\TransactionException;
use Well\DBBundle\Query\Query;
use Well\DBBundle\Query\QueryInterface;

/**
 * Database client
 */
class Client
{
    /** @var string DSN format */
    private static $dsn_format = 'mysql:host=%1$s;port=%2$s;dbname=%3$s;charset=%4$s';

    /** @var PDO PDO object */
    private $pdo;

    /** @var string PDO DSN string */
    private $dsn;

    /** @var string Database username */
    private $username;

    /** @var string Password */
    private $password;

    /** @var array Pre queries */
    private $preQueries;

    /** @var LoggerInterface Psr logger */
    private $logger;

    /**
     * Constructor
     *
     * @param string $host Host
     * @param int $port Port
     * @param string $dbname Database name
     * @param string $username Database user
     * @param string $password Password
     * @param string $charset Charset
     * @param array $preQueries Pre queries
     * @param array $options PDO options
     * @param LoggerInterface $logger Psr logger
     */
    public function __construct(
        string $host,
        int $port,
        string $dbname,
        string $username,
        string $password,
        string $charset,
        array $preQueries,
        array $options = [],
        LoggerInterface $logger = null
    ) {
        $this->dsn = sprintf(self::$dsn_format, $host, $port, $dbname, $charset);
        $this->username = $username;
        $this->password = $password;

        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
        $options[PDO::ATTR_EMULATE_PREPARES] = 0;
        $options[PDO::MYSQL_ATTR_DIRECT_QUERY] = 0;
        $this->options = $options;

        $this->preQueries = $preQueries;

        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * Connect to database
     *
     * @return Client
     * @throws ConnectionException
     */
    public function connect(): Client
    {
        if (null === $this->pdo) {
            try {
                $this->pdo = new PDO(
                    $this->dsn,
                    $this->username,
                    $this->password,
                    $this->options
                );
            } catch (PDOException $exception) {
                $this->logger->emergency('Database connection failed.', [
                    'code'    => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'dsn'     => $this->dsn,
                ]);

                throw new ConnectionException($exception);
            }
            foreach ($this->preQueries as $preQuery) {
                $this->exec($preQuery);
            }
        }

        return $this;
    }

    /**
     * Reconnect to database
     *
     * @return Client
     * @throws ConnectionException
     */
    public function reconnect(): Client
    {
        $this->pdo = null;
        $this->connect();

        return $this;
    }

    /**
     * Returns initialized PDO object
     *
     * @return PDO Native PDO
     * @throws ConnectionException
     */
    public function getPDO(): PDO
    {
        if (null === $this->pdo) {
            $this->connect();
        };

        return $this->pdo;
    }

    /**
     * Getter for statement by raw SQL statement
     *
     * @param string $statement Raw SQL statement
     * @return Statement
     */
    public function prepare(string $statement): Statement
    {
        $query = new Query($statement);

        return $this->query($query);
    }

    /**
     * Getter for statement by query
     *
     * @param QueryInterface $query Query
     * @return Statement
     */
    public function query(QueryInterface $query): Statement
    {
        return new Statement($this->getPDO(), $query, $this->dsn, $this->logger);
    }

    /**
     * Initiates a transaction
     *
     * @return void
     * @throws TransactionException
     */
    public function beginTransaction()
    {
        try {
            $this->getPDO()->beginTransaction();
        } catch (PDOException $exception) {
            $this->logger->error('Begin transaction error.', [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'dsn'     => $this->dsn,
            ]);

            throw new TransactionException($exception);
        }
    }

    /**
     * Commits a transaction
     *
     * @return void
     * @throws TransactionException
     */
    public function commit()
    {
        try {
            $this->getPDO()->commit();
        } catch (PDOException $exception) {
            $this->logger->error('Commit transaction error.', [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'dsn'     => $this->dsn,
            ]);

            throw new TransactionException($exception);
        }
    }

    /**
     * Rolls back a transaction
     *
     * @return void
     * @throws TransactionException
     */
    public function rollback()
    {
        try {
            $this->getPDO()->rollBack();
        } catch (PDOException $exception) {
            $this->logger->error('Rollback transaction error.', [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'dsn'     => $this->dsn,
            ]);

            throw new TransactionException($exception);
        }
    }

    /**
     * Checks if inside a transaction
     *
     * @return bool
     * @throws DBException
     */
    public function inTransaction(): bool
    {
        try {
            return $this->getPDO()->inTransaction();
        } catch (PDOException $exception) {
            $this->logger->error('Checks if inside a transaction error.', [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'dsn'     => $this->dsn,
            ]);

            throw new DBException($exception);
        }
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @param string|null $sequence_name Sequence name
     * @return int
     * @throws DBException
     */
    public function lastInsertId(string $sequence_name = null): int
    {
        try {
            return (int) $this->getPDO()->lastInsertId($sequence_name);
        } catch (PDOException $exception) {
            $this->logger->error('Retrieve last insert ID error.', [
                'code'          => $exception->getCode(),
                'message'       => $exception->getMessage(),
                'dsn'           => $this->dsn,
                'sequence_name' => $sequence_name,
            ]);

            throw new DBException($exception);
        }
    }

    /**
     * Execute an SQL statement and return the number of affected rows
     *
     * @param string $statement SQL statement
     * @return int
     * @throws DBException
     */
    public function exec(string $statement): int
    {
        try {
            $this->logger->debug('Start execute raw SQL statement.', [
                'dsn' => $this->dsn,
                'sql' => $statement,
            ]);

            $result = $this->getPDO()->exec($statement);
            $this->logger->debug('Raw SQL statement successfully executed.');

            return $result;
        } catch (PDOException $exception) {
            $statement = preg_replace('#[\n\r\s\t]+#', ' ', $statement);
            $this->logger->error('Execute raw SQL statement error.', [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'dsn'     => $this->dsn,
                'sql'     => trim($statement),
            ]);

            throw new ExecuteException($exception);
        }
    }

    /**
     * Close db connection
     */
    public function close()
    {
        $this->pdo = null;
        $this->logger->info('Close DB: ' . $this->dsn);
    }
}
