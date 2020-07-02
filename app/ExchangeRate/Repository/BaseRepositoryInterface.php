<?php


namespace App\ExchangeRate\Repository;


use App\ExchangeRate\Model\ExchangeRateCollection;

interface BaseRepositoryInterface
{
    public function getAllExchangeRates(): ExchangeRateCollection;
}