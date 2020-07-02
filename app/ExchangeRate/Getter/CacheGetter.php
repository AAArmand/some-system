<?php


namespace App\ExchangeRate\Getter;


use App\ExchangeRate\Model\ExchangeRateCollection;
use App\ExchangeRate\Repository\CreatableRepositoryInterface;

class CacheGetter extends AbstractGetter
{
    /**
     * @var CreatableRepositoryInterface
     */
    private CreatableRepositoryInterface $cacheRepository;

    public function __construct(CreatableRepositoryInterface $cacheRepository)
    {
        $this->cacheRepository = $cacheRepository;
    }

    /**
     * @return ExchangeRateCollection
     */
    public function getActualExchangeRates(): ExchangeRateCollection
    {
        $exchangeRates = $this->cacheRepository->getAllExchangeRates();

        if ($exchangeRates->isEmpty()) {
            return parent::getActualExchangeRates();
        }

        return $exchangeRates;
    }


}