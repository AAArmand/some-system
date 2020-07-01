<?php

namespace Tests\ExchangeRate;

use App\ExchangeRate\Model\ExchangeRate;
use App\ExchangeRate\Model\ExchangeRateCollection;
use App\ExchangeRate\Repository\CacheRepository;
use App\ExchangeRate\Repository\DBRepository;
use App\ExchangeRate\Repository\HttpRepository;
use App\ExchangeRate\Storage;
use Carbon\Carbon;
use Prophecy\Argument;
use Tests\TestCase;

class StorageTest extends TestCase
{

    public function testGetExchangeRates_hasRatesInCache_returnIt(): void
    {
        $expectedCollection = new ExchangeRateCollection();
        $expectedCollection->add(new ExchangeRate(1.21, 1.22, Carbon::now()));

        $cacheRepository = $this->prophesize(CacheRepository::class);
        $cacheRepository
            ->getAllExchangeRates()
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);

        $dbRepository = $this->prophesize(DBRepository::class);
        $dbRepository
            ->getAllExchangeRates()
            ->shouldNotBeCalled();
        $dbRepository
            ->createExchangeRates(Argument::any())
            ->shouldNotBeCalled();
        $httpRepository = $this->prophesize(HttpRepository::class);
        $httpRepository
            ->getAllExchangeRates()
            ->shouldNotBeCalled();

        $actualCollection = (new Storage(
            $cacheRepository->reveal(),
            $dbRepository->reveal(),
            $httpRepository->reveal()
        ))->getExchangeRates();

        $this->assertEquals($expectedCollection, $actualCollection);
    }

    public function testGetExchangeRates_hasNotRatesInCacheAndHasInDB_returnFromDBAndCreateInCache(): void
    {
        $emptyCollection = new ExchangeRateCollection();
        $expectedCollection = new ExchangeRateCollection();
        $expectedCollection->add(new ExchangeRate(1.21, 1.22, Carbon::now()));

        $cacheRepository = $this->prophesize(CacheRepository::class);
        $cacheRepository
            ->getAllExchangeRates()
            ->willReturn($emptyCollection)
            ->shouldBeCalledTimes(1);
        $cacheRepository
            ->createExchangeRates(Argument::is($expectedCollection))
            ->shouldBeCalledTimes(1);

        $dbRepository = $this->prophesize(DBRepository::class);
        $dbRepository
            ->getAllExchangeRates()
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);
        $dbRepository
            ->createExchangeRates(Argument::any())
            ->shouldNotBeCalled();
        $httpRepository = $this->prophesize(HttpRepository::class);
        $httpRepository
            ->getAllExchangeRates()
            ->shouldNotBeCalled();

        $actualCollection = (new Storage(
            $cacheRepository->reveal(),
            $dbRepository->reveal(),
            $httpRepository->reveal()
        ))->getExchangeRates();

        $this->assertEquals($expectedCollection, $actualCollection);
    }

    public function testGetExchangeRates_hasNotRatesInCacheAndHasNotInDBAndHasInHttp_returnFromHttpAndCreateInCacheAndCreateInDB(): void
    {
        $emptyCollection = new ExchangeRateCollection();
        $expectedCollection = new ExchangeRateCollection();
        $expectedCollection->add(new ExchangeRate(1.21, 1.22, Carbon::now()));

        $cacheRepository = $this->prophesize(CacheRepository::class);
        $cacheRepository
            ->getAllExchangeRates()
            ->willReturn($emptyCollection)
            ->shouldBeCalledTimes(1);
        $cacheRepository
            ->createExchangeRates(Argument::is($expectedCollection))
            ->shouldBeCalledTimes(1);

        $dbRepository = $this->prophesize(DBRepository::class);
        $dbRepository
            ->getAllExchangeRates()
            ->willReturn($emptyCollection)
            ->shouldBeCalledTimes(1);
        $dbRepository
            ->createExchangeRates(Argument::is($expectedCollection))
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);
        $httpRepository = $this->prophesize(HttpRepository::class);
        $httpRepository
            ->getAllExchangeRates()
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);

        $actualCollection = (new Storage(
            $cacheRepository->reveal(),
            $dbRepository->reveal(),
            $httpRepository->reveal()
        ))->getExchangeRates();

        $this->assertEquals($expectedCollection, $actualCollection);
    }
}
