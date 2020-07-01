<?php

namespace Tests\Http\Controllers\ExchangeRate;

use App\ExchangeRate\Storage;
use App\Http\Controllers\ExchangeRate\ExchangeRateController;
use Tests\TestCase;

class ExchangeRateControllerTest extends TestCase
{
    public function testGetActualRates_callGetExchangeRatesFromStorage(): void
    {
        $storage = $this->prophesize(Storage::class);

        $storage->getExchangeRates()->shouldBeCalledTimes(1);

        (new ExchangeRateController($storage->reveal()))->getActualRates();
    }
}
