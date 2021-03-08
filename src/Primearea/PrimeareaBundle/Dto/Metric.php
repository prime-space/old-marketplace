<?php namespace Primearea\PrimeareaBundle\Dto;

class Metric
{
    public $id;
    public $metricTypeId;
    public $relateId;
    public $ip;
    public $userAgent;
    public $createdTs;

    public function __construct()
    {
        $this->createdTs = $this->createdTs ? new \DateTime($this->createdTs) : null;
    }

    public static function create(
        int $metricTypeId,
        int $relateId,
        string $ip,
        string $userAgent
    ): self {
        $item = new self();
        $item->metricTypeId = $metricTypeId;
        $item->relateId = $relateId;
        $item->ip = $ip;
        $item->userAgent = $userAgent;

        return $item;
    }
}
