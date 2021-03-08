<?php namespace Primearea\PrimeareaBundle\Aggregator;

use RuntimeException;

class AggregatorProvider
{
    /**
     * @var AggregatorInterface[]
     */
    private $aggregators = [];

    public function addAggregator(AggregatorInterface $aggregator, string $name)
    {
        $this->aggregators[$name] = $aggregator;
    }

    public function getAggregator($name): AggregatorInterface
    {
        if (!isset($this->aggregators[$name])) {
            throw new RuntimeException("Aggregator with name '$name' is missing");
        }

        $aggregator = $this->aggregators[$name];

        return $aggregator;
    }
}
