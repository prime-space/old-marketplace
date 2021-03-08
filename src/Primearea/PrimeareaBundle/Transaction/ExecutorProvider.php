<?php namespace Primearea\PrimeareaBundle\Transaction;

use RuntimeException;

class ExecutorProvider
{
    /**
     * @var ExecutorInterface[]
     */
    private $executors = [];

    public function addExecutor(ExecutorInterface $executor, string $type)
    {
        $this->executors[$type] = $executor;
    }

    public function getExecutor($type): ExecutorInterface
    {
        if (!isset($this->executors[$type])) {
            throw new RuntimeException("Transaction executor with code '$type' is missing");
        }

        $executor = $this->executors[$type];

        return $executor;
    }
}
