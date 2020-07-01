<?php

namespace Tests\ExchangeRate\Model;

use App\ExchangeRate\Model\ExchangeRate;
use Carbon\Carbon;
use Tests\TestCase;

class ExchangeRateTest extends TestCase
{
    public function testPropertyMapping_hasProperties_returnItCorrectly(): void
    {
        $time = Carbon::now();
        $purchaseRate = 1.23;
        $sellingRate = 1.46;
        $exchangeRates = new ExchangeRate($purchaseRate,$sellingRate, $time);

        $this->assertEquals($time, $exchangeRates->updatedAt());
        $this->assertEquals($purchaseRate, $exchangeRates->purchaseRate());
        $this->assertEquals($sellingRate, $exchangeRates->sellingRate());
    }
}
