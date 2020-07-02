<?php


namespace App\ExchangeRate\Getter;


use App\ExchangeRate\Model\ExchangeRateCollection;
use App\ExchangeRate\Repository\BaseRepositoryInterface;
use App\ExchangeRate\Repository\CreatableRepositoryInterface;

class HttpGetter extends AbstractGetter
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
        BaseRepositoryInterface $httpRepository
    )
    {
        $this->cacheRepository    = $cacheRepository;
        $this->databaseRepository = $databaseRepository;
        $this->httpRepository     = $httpRepository;
    }

    /**
     * @return ExchangeRateCollection
     */
    public function getActualExchangeRates(): ExchangeRateCollection
    {
        $exchangeRates = $this->httpRepository->getAllExchangeRates();

        if ($exchangeRates->isEmpty()) {
            return parent::getActualExchangeRates();
        }

        $exchangeRates = $this->databaseRepository->createExchangeRates($exchangeRates);
        $this->cacheRepository->createExchangeRates($exchangeRates);

        return $exchangeRates;
    }


}