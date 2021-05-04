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

    /**
     * @param string $date_from
     * @param string $date_to
     *
     * @return CurrencyTimeSeriesRates
     */
    public function timeSeries(string $date_from, string $date_to)
    {
        return new CurrencyTimeSeriesRates($date_from, $date_to);
    }
}