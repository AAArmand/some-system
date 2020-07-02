<?php

namespace Tests\ExchangeRate\Getter;

use App\ExchangeRate\Getter\CacheGetter;
use App\ExchangeRate\Getter\DBGetter;
use App\ExchangeRate\Model\ExchangeRate;
use App\ExchangeRate\Model\ExchangeRateCollection;
use App\ExchangeRate\Repository\CacheRepository;
use Carbon\Carbon;
use Tests\TestCase;

class CacheGetterTest extends TestCase
{

    public function testGetActualExchangeRates_hasRates_returnIt(): void
    {
        $expectedCollection = new ExchangeRateCollection();
        $expectedCollection->add(new ExchangeRate(1.21, 1.22, Carbon::now()));

        $cacheRepository = $this->prophesize(CacheRepository::class);
        $cacheRepository
            ->getAllExchangeRates()
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);

        $actualCollection = (new CacheGetter($cacheRepository->reveal()))->getActualExchangeRates();

        $this->assertEquals($expectedCollection, $actualCollection);
    }

    public function testGetActualExchangeRates_hasNotRates_returnFromNextGetter(): void
    {
        $emptyCollection = new ExchangeRateCollection();
        $expectedCollection = new ExchangeRateCollection();
        $expectedCollection->add(new ExchangeRate(1.21, 1.22, Carbon::now()));

        $cacheRepository = $this->prophesize(CacheRepository::class);
        $cacheRepository
            ->getAllExchangeRates()
            ->willReturn($emptyCollection)
            ->shouldBeCalledTimes(1);
        $dbGetter = $this->prophesize(DBGetter::class);
        $dbGetter
            ->getActualExchangeRates()
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);
        $cacheGetter = new CacheGetter($cacheRepository->reveal());
        $cacheGetter->setNext($dbGetter->reveal());

        $actualCollection = $cacheGetter->getActualExchangeRates();

        $this->assertEquals($expectedCollection, $actualCollection);
    }
}
