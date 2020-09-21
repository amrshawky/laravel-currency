<?php

namespace AmrShawky\Currency\Tests;

use AmrShawky\Currency\CurrencyHistoricalRates;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CurrencyHistoricalRatesTest extends TestCase
{
    use ClientMock;

    public function historicalRates()
    {
        return (new CurrencyHistoricalRates('2020-08-10', $this->client))
            ->symbols(['USD','CZK', 'EGP', 'GBP'])
            ->base('EUR')
            ->round(2)
            ->amount(30)
            ->get();
    }

    private function successMock()
    {
        return $this->mock([
            new Response(200, [], json_encode([
                'success'   => true,
                'rates'     => [
                    'USD' => 1.5
                ]
            ]))
        ]);
    }

    /**
     * @test
     */
    public function it_returns_null_when_it_fails()
    {
        $this->client = $this->mock([
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
            new Response(500),
            new Response(400)
        ]);

        $this->assertNull($this->historicalRates());
        $this->assertNull($this->historicalRates());
        $this->assertNull($this->historicalRates());
    }

    /**
     * @test
     */
    public function dynamic_method_call_adds_to_query_param_if_method_is_available()
    {
        $this->client = $this->successMock();

        $result = $this->historicalRates();

        $this->assertEquals(['USD' => 1.5], $result);
    }

    /**
     * @test
     */
    public function dynamic_method_call_fails_if_method_is_not_available()
    {
        $this->expectException(\Exception::class);
        $this->client = $this->successMock();

        (new CurrencyHistoricalRates('2020-02-05', $this->client))
            ->test(1)
            ->get();
    }

    /**
     * @test
     */
    public function dynamic_method_call_fails_if_method_call_has_no_parameters()
    {
        $this->expectException(\Exception::class);
        $this->client = $this->successMock();

        (new CurrencyHistoricalRates('2020-01-01', $this->client))
            ->base()
            ->round()
            ->get();
    }
}