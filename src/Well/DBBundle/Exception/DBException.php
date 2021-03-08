<?php namespace Well\DBBundle\Exception;

use PDOException;
use RuntimeException;

/**
 * Wrapper for PDOException
 */
class DBException extends RuntimeException implements DBExceptionInterface
{
    /**
     * Constructor
     *
     * @param PDOException $exception PDO exception
     */
    public function __construct(PDOException $exception)
    {
        parent::__construct($exception->getMessage(), (int) $exception->getCode(), $exception);
    }
}
