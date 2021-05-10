<?php

namespace AmrShawky\Currency;

use GuzzleHttp\Client;

class CurrencyFluctuations extends CurrencyRates
{
    /**
     * CurrencyFluctuations constructor.
     *
     * @param string      $start_date
     * @param string      $end_date
     * @param Client|null $client
     */
    public function __construct(string $start_date, string $end_date, ?Client $client = null)
    {
        parent::__construct($client);
        $this->base_url = "https://api.exchangerate.host/fluctuation";
        $this->params['start_date'] = $start_date;
        $this->params['end_date']   = $end_date;
    }

    /**
     * @param object $response
     *
     * @return mixed|null
     */
    protected function getResults(object $response)
    {
        if (!empty($fluctuations = (array) $response->rates)) {
            foreach ($fluctuations as $currency => $results) {
                $fluctuations[$currency] = (array) $results;
            }
            unset($response->rates);

            return $fluctuations;
        }

        return null;
    }
}