<?php


namespace App\ExchangeRate\Getter;


use App\ExchangeRate\Model\ExchangeRateCollection;
use App\ExchangeRate\Repository\CreatableRepositoryInterface;

class DBGetter extends AbstractGetter
{
    /**
     * @var CreatableRepositoryInterface
     */
    private CreatableRepositoryInterface $cacheRepository;

    /**
     * @var CreatableRepositoryInterface
     */
    private CreatableRepositoryInterface $databaseRepository;

    public function __construct(CreatableRepositoryInterface $cacheRepository, CreatableRepositoryInterface $databaseRepository)
    {
        $this->cacheRepository    = $cacheRepository;
        $this->databaseRepository = $databaseRepository;
    }

    /**
     * @return ExchangeRateCollection
     */
    public function getActualExchangeRates(): ExchangeRateCollection
    {
        $exchangeRates = $this->databaseRepository->getAllExchangeRates();

        if ($exchangeRates->isEmpty()) {
            return parent::getActualExchangeRates();
        }

        $this->cacheRepository->createExchangeRates($exchangeRates);

        return $exchangeRates;
    }


}