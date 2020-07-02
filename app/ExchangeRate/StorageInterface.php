<?php


namespace App\ExchangeRate;


use App\ExchangeRate\Model\ExchangeRateCollection;

interface StorageInterface
{
    /**
     * @return ExchangeRateCollection
     */
    public function getExchangeRates(): ExchangeRateCollection;
}