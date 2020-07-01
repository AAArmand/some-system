<?php


namespace App\ExchangeRate;


use App\ExchangeRate\Getter\CacheGetter;
use App\ExchangeRate\Getter\DBGetter;
use App\ExchangeRate\Getter\HttpGetter;
use App\ExchangeRate\Model\ExchangeRateCollection;
use App\ExchangeRate\Repository\BaseRepositoryInterface;
use App\ExchangeRate\Repository\CreatableRepositoryInterface;

class Storage implements StorageInterface
{
    /**
     * @var CreatableRepositoryInterface
     */
    private CreatableRepositoryInterface $cacheRepository;

    /**
     * @var CreatableRepositoryInterface
     */
    private CreatableRepositoryInterface $databaseRepository;

    /**
     * @var BaseRepositoryInterface
     */
    private BaseRepositoryInterface $httpRepository;

    public function __construct(
        CreatableRepositoryInterface $cacheRepository,
        CreatableRepositoryInterface $databaseRepository,
        BaseRepositoryInterface $httpRepository)
    {
        $this->cacheRepository    = $cacheRepository;
        $this->databaseRepository = $databaseRepository;
        $this->httpRepository     = $httpRepository;
    }

    /**
     * @return ExchangeRateCollection
     */
    public function getExchangeRates(): ExchangeRateCollection
    {
        $cacheGetter = new CacheGetter($this->cacheRepository);
        $cacheGetter
            ->setNext(new DBGetter($this->cacheRepository, $this->databaseRepository))
            ->setNext(new HttpGetter($this->cacheRepository, $this->databaseRepository, $this->httpRepository));

        return $cacheGetter->getActualExchangeRates();
    }
}