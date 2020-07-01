<?php

namespace Tests\ExchangeRate\Getter;

use App\ExchangeRate\Getter\CacheGetter;
use App\ExchangeRate\Getter\DBGetter;
use App\ExchangeRate\Model\ExchangeRate;
use App\ExchangeRate\Model\ExchangeRateCollection;
use App\ExchangeRate\Repository\CacheRepository;
use App\ExchangeRate\Repository\DBRepository;
use Carbon\Carbon;
use Prophecy\Argument;
use Tests\TestCase;

class DBGetterTest extends TestCase
{
    public function testGetActualExchangeRates_hasRates_returnItAndCreateItInCache(): void
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
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);
        $dbRepository
            ->createExchangeRates(Argument::any())
            ->shouldNotBeCalled();

        $actualCollection = (new DBGetter($cacheRepository->reveal(), $dbRepository->reveal()))->getActualExchangeRates();

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
            ->getAllExchangeRates()
            ->willReturn($emptyCollection)
            ->shouldBeCalledTimes(1);
        $dbRepository
            ->createExchangeRates(Argument::any())
            ->shouldNotBeCalled();

        $cacheGetter = $this->prophesize(CacheGetter::class);
        $cacheGetter
            ->getActualExchangeRates()
            ->willReturn($expectedCollection)
            ->shouldBeCalledTimes(1);
        $dbGetter = new DBGetter($cacheRepository->reveal(), $dbRepository->reveal());
        $dbGetter->setNext($cacheGetter->reveal());

        $actualCollection = $dbGetter->getActualExchangeRates();

        $this->assertEquals($expectedCollection, $actualCollection);
    }
}
