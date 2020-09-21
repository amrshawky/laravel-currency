<?php

namespace AmrShawky\Currency;

use GuzzleHttp\Client;

class CurrencyLatestRates extends CurrencyRates
{
    /**
     * CurrencyLatestRates constructor.
     *
     * @param Client|null $client
     */
    public function __construct(?Client $client = null)
    {
        parent::__construct($client);
        $this->base_url = "https://api.exchangerate.host/latest";
    }
}