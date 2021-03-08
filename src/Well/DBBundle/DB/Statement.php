<?php namespace Well\DBBundle\DB;

use PDO;
use PDOException;
use PDOStatement;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Well\DBBundle\Exception\ExecuteException;
use Well\DBBundle\Exception\EntryNotFoundException;
use Well\DBBundle\Query\QueryInterface;

/**
 * Prepare and execute query
 */
class Statement
{
    /** @var PDOStatement PDO statement */
    private $statement;

    /** @var QueryInterface Query */
    private $query;

    /** @var string PDO DSN string */
    private $dsn;

    /** @var LoggerInterface Psr logger */
    private $logger;

    /**
     * Constructor
     *
     * @param PDO $pdo PDO object
     * @param QueryInterface $query Query
     * @param string $dsn PDO DSN string
     * @param LoggerInterface $logger Psr logger
     */
    public function __construct(PDO $pdo, QueryInterface $query, string $dsn, LoggerInterface $logger = null)
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->dsn = $dsn;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * Getter for query
     *
     * @return QueryInterface
     */
    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    /**
     * Getter for PDO statement
     *
     * @return PDOStatement
     */
    public function getStatement(): PDOStatement
    {
        return $this->statement;
    }

    /**
     * Execute current statement
     *
     * @param array $parameters Query parameters
     * @return Statement
     * @throws ExecuteException
     */
    public function execute(array $parameters = []): Statement
    {
        try {
            $parameters = $this->query->getParameters() + $parameters;
            if (null === $this->statement) {
                $statement = $this->query->getStatement();
                $this->logger->debug('Start prepare SQL statement.', [
                    'statement' => $statement,
                    'dsn'       => $this->dsn,
                    'parameters' => $parameters,
                ]);

                $this->statement = $this->pdo->prepare($statement);
            } else {
                $this->logger->debug('Input parameters.', [
                    'parameters' => $parameters,
                ]);
            }

            $this->statement->execute($parameters);
        } catch (PDOException $exception) {
            $this->logger->error('Execute SQL statement error.', [
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'dsn'     => $this->dsn,
                'sql'     => $this->compileSQL(
                    $this->query->getStatement(),
                    $this->query->getParameters() + $parameters
                )
            ]);

            throw new ExecuteException($exception);
        }

        return $this;
    }

    /**
     * Fetch single array
     *
     * @return array
     * @throws EntryNotFoundException
     */
    public function fetchArray(): array
    {
        $array = $this->statement->fetch(PDO::FETCH_ASSOC);
        if (false === $array) {
            throw new EntryNotFoundException();
        }

        return $array;
    }

    /**
     * Fetch single object
     *
     * @return object
     * @throws EntryNotFoundException
     */
    public function fetchObject($class)
    {
        $object = $this->statement->fetchObject($class);
        if (false === $object) {
            throw new EntryNotFoundException();
        }

        return $object;
    }

    /**
     * Fetch array of arrays
     *
     * @param int $fetch_style Pdo fetch style
     * @return array[]
     */
    public function fetchArrays(int $fetch_style = 0): array
    {
        return $this->statement->fetchAll($fetch_style);
    }

    /**
     * Fetch column value
     *
     * @param int $column_number Column number
     * @return mixed
     */
    public function fetchColumn(int $column_number = 0)
    {
        $value = $this->statement->fetch(PDO::FETCH_COLUMN, $column_number);
        if (false === $value) {
            throw new EntryNotFoundException();
        }

        return $value;
    }

    /**
     * Fetch columns values
     *
     * @param int $column_number Column number
     * @return array
     */
    public function fetchColumns(int $column_number = 0): array
    {
        return $this->statement->fetchAll(PDO::FETCH_COLUMN, $column_number);
    }

    /**
     * Getter for affected rows
     *
     * @return int
     */
    public function affectedRows(): int
    {
        return $this->statement->rowCount();
    }

    /**
     * Compile SQL by SQL statement and input parameters
     *
     * @param string $statement SQL statement
     * @param array $parameters Input parameters
     * @return string
     */
    private function compileSQL(string $statement, array $parameters): string
    {
        $statement = preg_replace('#[\n\r\s\t]+#', ' ', $statement);
        foreach ($parameters as $key => $value) {
            $statement = str_replace(':' . $key, $this->pdo->quote($value), $statement);
        }

        return trim($statement);
    }
}
