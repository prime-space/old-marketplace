<?php namespace Primearea\PrimeareaBundle\Repository;

use Primearea\PrimeareaBundle\Dto\Metric;
use Well\DBBundle\DB\DefaultDbClientTrait;

class MetricRepository
{
    use DefaultDbClientTrait;

    public function create(Metric $metric)
    {
        $this->defaultDbClient->prepare(<<<SQL
INSERT INTO metric
  (`metricTypeId`, `relateId`, `ip`, `userAgent`)
VALUES
  (:metricTypeId, :relateId, :ip, :userAgent)
SQL
        )
            ->execute([
                'metricTypeId' => $metric->metricTypeId,
                'relateId' => $metric->relateId,
                'ip' => $metric->ip,
                'userAgent' => $metric->userAgent,
            ]);

        $metric->id = $this->defaultDbClient->lastInsertId();
    }
}
