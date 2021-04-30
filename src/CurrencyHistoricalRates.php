<?php

namespace AmrShawky\Currency;

use GuzzleHttp\Client;

class CurrencyHistoricalRates extends CurrencyRates
{
    /**
     * CurrencyHistoricalRates constructor.
     *
     * @param string      $date
     * @param Client|null $client
     */
    public function __construct(string $date, ?Client $client = null)
    {
        parent::__construct($client);
        $this->base_url = "https://api.exchangerate.host/{$date}";
    }
}