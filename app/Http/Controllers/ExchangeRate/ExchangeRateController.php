<?php

namespace App\Http\Controllers\ExchangeRate;

use App\ExchangeRate\StorageInterface;
use App\Http\Controllers\Controller;
use App\ExchangeRate\Model\ExchangeRateCollection;

class ExchangeRateController extends Controller
{
    /**
     * @var StorageInterface
     */
    private StorageInterface $exchangeRateStorage;

    public function __construct(StorageInterface $exchangeRateStorage)
    {
        $this->exchangeRateStorage = $exchangeRateStorage;
    }

    /**
     * @return ExchangeRateCollection
     */
    public function getActualRates(): ExchangeRateCollection
    {
        return $this->exchangeRateStorage->getExchangeRates();
    }
}