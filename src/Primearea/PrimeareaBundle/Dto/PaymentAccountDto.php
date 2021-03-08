<?php namespace Primearea\PrimeareaBundle\Dto;

class PaymentAccountDto
{
    public $id;
    public $paymentSystemId;
    public $name;
    public $config;
    public $weight;
    public $enabled;

    public function __construct()
    {
        $this->id = empty($this->id) ? null : (int)$this->id;
        $this->paymentSystemId = empty($this->paymentSystemId) ? null : (int)$this->paymentSystemId;
        $this->config = empty($this->config) ? [] : json_decode($this->config, true);
        $this->enabled = empty($this->enabled) ? [] : explode(',', $this->enabled);
    }

    public static function create($paymentSystemId, $name, $config, $weight, $enabled): self
    {
        $item = new self();
        $item->paymentSystemId = (int)$paymentSystemId;
        $item->name = $name;
        $item->config = $config;
        $item->weight = $weight;
        $item->enabled = $enabled;

        return $item;
    }
}

