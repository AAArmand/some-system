<?php


namespace App\ExchangeRate\Model;


use Carbon\Carbon;

class ExchangeRate
{
    /**
     * @var float
     */
    private float $purchaseRate;

    /**
     * @var float
     */
    private float $sellingRate;

    /**
     * @var Carbon
     */
    private Carbon $updatedAt;

    public function __construct(float $purchaseRate, float $sellingRate, Carbon $updatedAt)
    {
        $this->purchaseRate = $purchaseRate;
        $this->sellingRate  = $sellingRate;
        $this->updatedAt    = $updatedAt;
    }

    /**
     * @return float
     */
    public function purchaseRate(): float
    {
        return $this->purchaseRate;
    }

    /**
     * @return float
     */
    public function sellingRate(): float
    {
        return $this->sellingRate;
    }

    /**
     * @return Carbon
     */
    public function updatedAt(): Carbon
    {
        return $this->updatedAt;
    }
}