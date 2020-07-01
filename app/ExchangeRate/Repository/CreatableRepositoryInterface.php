<?php


namespace App\ExchangeRate\Repository;


use App\ExchangeRate\Model\ExchangeRateCollection;

interface CreatableRepositoryInterface extends BaseRepositoryInterface
{
    public function createExchangeRates(ExchangeRateCollection $exchangeRateCollection): ExchangeRateCollection;
}