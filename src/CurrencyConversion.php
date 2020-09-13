<?php

namespace AmrShawky\Currency;

use AmrShawky\Currency\Traits\HttpRequest;
use AmrShawky\Currency\Traits\ParamsOverload;
use GuzzleHttp\Client;

class CurrencyConversion
{
    use HttpRequest, ParamsOverload;

    /**
     * @var string
     */
    private $base_url  = 'https://api.exchangerate.host/convert';

    /**
     * Required base currency
     *
     * @var null
     */
    private $from = null;

    /**
     * Required target currency
     *
     * @var null
     */
    private $to = null;

    /**
     * @var float
     */
    private $amount = 1.00;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $query_params = [];

    private $available_params = [
        'round',
        'date',
        'source'
    ];

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
     * @throws \Exception
     *
     * @return float|null
     */
    public function get()
    {
        if (!$this->from) {
            throw new \Exception('Base currency is not specified!');
        }

        if (!$this->to) {
            throw new \Exception('Target currency is not specified!');
        }
        
        $this->buildQueryParams();

        $response = $this->request(
            $this->base_url,
            $this->query_params
        );

        return $response->result ?? null;
    }

    private function buildQueryParams()
    {
        $this->query_params = [
            'from'      => $this->from,
            'to'        => $this->to,
            'amount'    => $this->amount
        ];

        if (!empty($this->params)) {
            foreach ($this->params as $key => $param) {
                $this->query_params[$key] = $param;
            }
        }
    }
}