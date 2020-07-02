<?php


namespace App\ExchangeRate\Repository;


use App\ExchangeRate\Model\ExchangeRateCollection;

class HttpRepository implements BaseRepositoryInterface
{

    /**
     * @return ExchangeRateCollection
     */
    public function getAllExchangeRates(): ExchangeRateCollection
    {
        return new ExchangeRateCollection();
    }
}