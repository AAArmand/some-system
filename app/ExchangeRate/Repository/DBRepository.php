<?php


namespace App\ExchangeRate\Repository;


use App\ExchangeRate\Model\ExchangeRateCollection;

class DBRepository implements CreatableRepositoryInterface
{

    /**
     * @return ExchangeRateCollection
     */
    public function getAllExchangeRates(): ExchangeRateCollection
    {
        return new ExchangeRateCollection();
    }

    /**
     * @param ExchangeRateCollection $exchangeRateCollection
     * @return ExchangeRateCollection
     */
    public function createExchangeRates(ExchangeRateCollection $exchangeRateCollection): ExchangeRateCollection
    {
        return $exchangeRateCollection;
    }
}