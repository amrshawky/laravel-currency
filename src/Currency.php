<?php

namespace AmrShawky\Currency;

class Currency
{
    /**
     * @return CurrencyConversion
     */
    public function convert()
    {
        return new CurrencyConversion();
    }

    /**
     * @return CurrencyRatesProxy
     */
    public function rates()
    {
        return new CurrencyRatesProxy();
    }
}