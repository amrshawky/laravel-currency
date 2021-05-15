<?php

namespace AmrShawky\LaravelCurrency\Tests;

use AmrShawky\CurrencyConversion;
use AmrShawky\CurrencyFactory;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    use ClientMock;

    public $from = 'USD';

    public $to   = 'EUR';

    private function convert(?callable $callback = null)
    {
        $currency =  (new CurrencyFactory())
            ->convert($this->client)
            ->from($this->from)
            ->to($this->to)
            ->amount(1)
            ->when($callback, function (CurrencyConversion $currencyConversion) use ($callback) {
                return $callback($currencyConversion);
            });

        $currency->get();

        return  $currency;
    }

    private function successMock()
    {
        return $this->mock([
            new Response(200, [], json_encode(['success' => true, 'result' => 1.504]))
        ]);
    }

    protected function getClientFromObject(CurrencyConversion $currency)
    {
        $currency_reflection = new \ReflectionClass($currency);

        $property = $currency_reflection->getProperty('client');
        $property->setAccessible(true);

        return $property->getValue($currency);
    }

    /** @test */
    public function it_add_options_client()
    {
        $this->client = $this->successMock();

        $currency = $this->convert(function ($currency) {
           return $currency->withOptions([
                'timeout'        => 3.0,
               'allow_redirects' => [
                   'max' => 10
               ]
            ]);
        });

        $client = $this->getClientFromObject($currency);

        $this->assertSame($client->getConfig('timeout'), 3.0);
        $this->assertSame($client->getConfig('allow_redirects')['max'], 10);
    }

    /** @test */
    public function it_indicate_that_tls_should_not_be_verified()
    {
        $this->client = $this->successMock();

        $currency = $this->convert(function ($currency) {
            return $currency->withoutVerifying();
        });

        $client = $this->getClientFromObject($currency);

        $this->assertSame($client->getConfig('verify'), false);
    }
}