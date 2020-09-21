<?php

namespace AmrShawky\Currency;

class CurrencyRatesProxy
{
    /**
     * @return CurrencyLatestRates
     */
    public function latest()
    {
        return new CurrencyLatestRates();
    }

    /**
     * @param string $date
     *
     * @return CurrencyHistoricalRates
     */
    public function historical(string $date)
    {
        return new CurrencyHistoricalRates($date);
    }
}