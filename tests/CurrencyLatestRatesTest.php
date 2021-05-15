<?php

namespace AmrShawky\LaravelCurrency\Tests;

use AmrShawky\CurrencyFactory;
use AmrShawky\CurrencyLatestRates;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CurrencyLatestRatesTest extends TestCase
{
    use ClientMock, Throwable;

    private function latestRates()
    {
        return (new CurrencyFactory())
            ->rates()
            ->latest($this->client)
            ->symbols(['USD', 'EUR','CZK', 'EGP'])
            ->base('GBP')
            ->round(2)
            ->when($this->throw, function (CurrencyLatestRates $latestRates) {
                return $latestRates->throw($this->throw_callback ?? null);
            })
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
    public function it_returns_null_when_it_fails_and_throw_is_false()
    {
        $this->client = $this->mock([
            new RequestException('Error Communicating with Server', new Request('GET', 'test')),
            new Response(500),
            new Response(400)
        ]);

        $this->assertNull($this->latestRates());
        $this->assertNull($this->latestRates());
        $this->assertNull($this->latestRates());
    }


    /**
     * @test
     */
    public function it_throws_exception_when_http_fails_and_throw_is_true()
    {
        $this->expectException(\AmrShawky\Exceptions\RequestException::class);

        $this->client = $this->mock([
            new Response(500)
        ]);

        $this->throw();
        $this->latestRates();
    }

    /**
     * @test
     */
    public function it_throws_exception_when_networking_error_occurs_and_throw_is_true()
    {
        $this->expectException(RequestException::class);

        $this->client = $this->mock([
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $this->throw();
        $this->latestRates();
    }

    /**
     * @test
     */
    public function it_returns_conversion_rate_when_successful()
    {
        $this->client = $this->successMock();

        $this->assertEquals(['USD' => 1.5], $this->latestRates());
    }

    /**
     * @test
     */
    public function dynamic_method_call_adds_to_query_param_if_method_is_available()
    {
        $this->client = $this->successMock();

        $result = $this->latestRates();

        $this->assertEquals(['USD' => 1.5], $result);
    }

    /**
     * @test
     */
    public function dynamic_method_call_fails_if_method_is_not_available()
    {
        $this->expectException(\Exception::class);
        $this->client = $this->successMock();

        (new CurrencyFactory())
            ->rates()
            ->latest($this->client)
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

        (new CurrencyFactory())
            ->rates()
            ->latest($this->client)
            ->base()
            ->round()
            ->get();
    }
}