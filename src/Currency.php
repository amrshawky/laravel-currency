<?php

namespace AmrShawky\Currency;

use GuzzleHttp\Client;

class Currency
{
    public function convert()
    {
        return new CurrencyConversion(
            new Client()
        );
    }
}