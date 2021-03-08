<?php namespace Primearea\PrimeareaBundle\Dto;

class YandexOutputDto
{
    public $id;
    public $cashoutId;
    public $amount;
    public $fee;
    public $purse;
    public $createdTs;

    public function __construct()
    {
        $this->id = (int)$this->id;
        $this->cashoutId = (int)$this->cashoutId;
    }

    public static function create(
        int $cashoutId,
        string $amount,
        string $fee,
        string $purse
    ): self {
        $item = new self();
        $item->cashoutId = $cashoutId;
        $item->amount = $amount;
        $item->fee = $fee;
        $item->purse = $purse;

        return $item;
    }
}
