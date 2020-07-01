<?php


namespace App\ExchangeRate\Getter;


use App\ExchangeRate\Model\ExchangeRateCollection;

abstract class AbstractGetter implements GetterInterface
{
    /**
     * @var GetterInterface|null
     */
    private ?GetterInterface $nextGetter = null;

    /**
     * @param GetterInterface $getter
     * @return GetterInterface
     */
    public function setNext(GetterInterface $getter): GetterInterface
    {
        $this->nextGetter = $getter;

        return $getter;
    }

    /**
     * @return ExchangeRateCollection
     */
    public function getActualExchangeRates(): ExchangeRateCollection
    {
        if (!$this->nextGetter) {
            return new ExchangeRateCollection();
        }

        return $this->nextGetter->getActualExchangeRates();
    }
}