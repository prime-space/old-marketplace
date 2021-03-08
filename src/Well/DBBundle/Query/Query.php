<?php namespace Well\DBBundle\Query;

/**
 * Default query implementation
 */
class Query implements QueryInterface
{
    /** @var string SQL statement */
    private $statement;

    /**
     * Constructor
     *
     * @param string $statement SQL statement
     */
    public function __construct(string $statement)
    {
        $this->statement = $statement;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatement(): string
    {
        return $this->statement;
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters(): array
    {
        return [];
    }
}
