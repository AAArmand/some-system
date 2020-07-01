<?php

namespace Tests\ExchangeRate\Getter;

use App\ExchangeRate\Getter\CacheGetter;
use App\ExchangeRate\Getter\DBGetter;
use App\ExchangeRate\Getter\HttpGetter;
use App\ExchangeRate\Model\ExchangeRate;
use App\ExchangeRate\Model\ExchangeRateCollection;
use App\ExchangeRate\Repository\CacheRepository;
use App\ExchangeRate\Repository\DBRepository;
use App\ExchangeRate\Repository\HttpRepository;
use Carbon\Carbon;
use Prophecy\Argument;
use Tests\TestCase;

class HttpGetterTest extends TestCase
{
    public function testGetActualExchangeRates_hasRates_returnItAndCreateItInCacheAndInDB(): void
    {
        $expectedCollection = new ExchangeRateCollection();
        $expectedCollection->add(new ExchangeRate(1.21, 1.22, Carbon::now()));

        $cacheRepository = $this->prophesize(CacheRepository::class);
        $cacheRepository
            ->getAllExchangeRates()
            ->shouldNotBeCalled();
        $cacheRepository
            ->createExchangeRates(Argument::is($expectedCollection))
            ->shouldBeCalledTimes(1);

        $dbRepository = $this->prophesize(DBRepository::class);
        $dbRepository
            ->getAllExchangeRates()
            ->shouldNotBeCalled();
        $dbRepository
            ->createExchangeRates(Argument::is($expectedCollection))
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);

        $httpRepository = $this->prophesize(HttpRepository::class);
        $httpRepository
            ->getAllExchangeRates()
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);

        $actualCollection = (new HttpGetter($cacheRepository->reveal(), $dbRepository->reveal(), $httpRepository->reveal()))->getActualExchangeRates();

        $this->assertEquals($expectedCollection, $actualCollection);
    }

    public function testGetActualExchangeRates_hasNotRates_returnFromNextGetter(): void
    {
        $emptyCollection = new ExchangeRateCollection();
        $expectedCollection = new ExchangeRateCollection();
        $expectedCollection->add(new ExchangeRate(1.21, 1.22, Carbon::now()));

        $cacheRepository = $this->prophesize(CacheRepository::class);
        $cacheRepository
            ->createExchangeRates()
            ->shouldNotBeCalled();
        $cacheRepository
            ->getAllExchangeRates()
            ->shouldNotBeCalled();

        $dbRepository = $this->prophesize(DBRepository::class);
        $dbRepository
            ->createExchangeRates()
            ->shouldNotBeCalled();
        $dbRepository
            ->createExchangeRates()
            ->shouldNotBeCalled();

        $httpRepository = $this->prophesize(HttpRepository::class);
        $httpRepository
            ->getAllExchangeRates()
            ->willReturn($emptyCollection)
            ->shouldBeCalledTimes(1);

        $cacheGetter = $this->prophesize(CacheGetter::class);
        $cacheGetter
            ->getActualExchangeRates()
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);
        $dbGetter = new HttpGetter($cacheRepository->reveal(), $dbRepository->reveal(), $httpRepository->reveal());
        $dbGetter->setNext($cacheGetter->reveal());

        $actualCollection = $dbGetter->getActualExchangeRates();

        $this->assertEquals($expectedCollection, $actualCollection);
    }
}
