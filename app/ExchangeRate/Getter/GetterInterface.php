<?php


namespace App\ExchangeRate\Getter;


use App\ExchangeRate\Model\ExchangeRateCollection;

interface GetterInterface
{
    /**
     * @return ExchangeRateCollection
     */
    public function getActualExchangeRates(): ExchangeRateCollection;

    /**
     * @param GetterInterface $getter
     * @return GetterInterface
     */
    public function setNext(GetterInterface $getter): GetterInterface;
}