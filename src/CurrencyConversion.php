<?php

namespace AmrShawky\Currency;

use AmrShawky\Currency\Traits\HttpRequest;
use GuzzleHttp\Client;

class CurrencyConversion
{
    use HttpRequest;

    /**
     * @var string
     */
    private $base_url  = 'https://api.exchangerate.host/convert';

    /**
     * @var null
     */
    private $from = null;

    /**
     * @var null
     */
    private $to = null;

    /**
     * @var null
     */
    private $amount = null;

    /**
     * @var null
     */
    private $round = null;

    /**
     * @var null
     */
    private $query_params = [];

    /**
     * @var
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $currency
     *
     * @return $this
     */
    public function from(string $currency)
    {
        $this->from = $currency;
        return $this;
    }

    /**
     * @param $currency
     *
     * @return $this
     */
    public function to(string $currency)
    {
        $this->to = $currency;
        return $this;
    }

    /**
     * @param float $amount
     *
     * @return $this
     */
    public function amount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param int $places
     *
     * @return $this
     */
    public function round(int $places)
    {
        $this->round = $places;
        return $this;
    }

    /**
     * @return false|mixed
     * @throws \Exception
     */
    public function get()
    {
        if (!$this->from) {
            throw new \Exception('Base currency is not specified!');
        }

        if (!$this->to) {
            throw new \Exception('Target currency is not specified!');
        }

        if (!$this->amount) {
            throw new \Exception('Amount to convert is not specified!');
        }

        $this->buildQueryParams();

        $response = $this->request(
            $this->client,
            $this->base_url,
            $this->query_params
        );

        return $response->result ?? null;
    }

    private function buildQueryParams()
    {
        $this->query_params = [
            'from'  => $this->from,
            'to'    => $this->to
        ];

        if ($this->amount !== null) {
            $this->query_params['amount'] = $this->amount;
        }

        if ($this->round !== null) {
            $this->query_params['places'] = $this->round;
        }
    }
}