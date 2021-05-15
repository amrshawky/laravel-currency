<?php

namespace AmrShawky\LaravelCurrency\Tests;

use AmrShawky\CurrencyConversion;
use AmrShawky\CurrencyFactory;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CurrencyConversionTest extends TestCase
{
    use ClientMock, Throwable;

    public $from = 'USD';

    public $to   = 'EUR';

    private function convert()
    {
        return (new CurrencyFactory())
            ->convert($this->client)
            ->from($this->from)
            ->to($this->to)
            ->amount(1)
            ->when($this->throw, function (CurrencyConversion $currencyConversion) {
                return $currencyConversion->throw($this->throw_callback ?? null);
            })
            ->get();
    }

    private function successMock()
    {
        return $this->mock([
            new Response(200, [], json_encode(['success' => true, 'result' => 1.504]))
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

        $this->assertNull($this->convert());
        $this->assertNull($this->convert());
        $this->assertNull($this->convert());
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
        $this->convert();
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
        $this->convert();
    }

    /**
     * @test
     */
    public function it_throws_exception_it_fails_and_throw_is_true_with_a_callback()
    {
        $this->expectException(\AmrShawky\Exceptions\RequestException::class);

        $this->client = $this->mock([
            new Response(500)
        ]);

        $this->throw(function ($request, $e) {
            //
        });

        $this->convert();
    }
    
    /**
     * @test
     */
    public function it_returns_conversion_rate_when_successful()
    {
        $this->client = $this->successMock();

        $this->assertEquals(1.504, $this->convert());
    }

    /**
     * @test
     */
    public function it_throws_exception_when_base_currency_is_missing()
    {
        $this->expectException(\Exception::class);

        $this->client = $this->successMock();

        (new CurrencyFactory())
            ->convert($this->client)
            ->to($this->to)
            ->amount(1)
            ->get();

    }

    /**
     * @test
     */
    public function it_throws_exception_when_target_currency_is_missing()
    {
        $this->expectException(\Exception::class);

        $this->client = $this->successMock();

        (new CurrencyFactory())
            ->convert($this->client)
            ->from($this->from)
            ->amount(1)
            ->get();
    }

    /**
     * @test
     */
    public function amount_has_default_when_missing()
    {
        $this->client = $this->successMock();

        $result = (new CurrencyFactory())
                    ->convert($this->client)
                    ->from($this->from)
                    ->to($this->to)
                    ->get();

        $this->assertEquals(1.504, $result);
    }

    /**
     * @test
     */
    public function dynamic_method_call_adds_to_query_param_if_method_is_available()
    {
        $this->client = $this->successMock();

        $result = (new CurrencyFactory())
            ->convert($this->client)
            ->from($this->from)
            ->to($this->to)
            ->round(1)
            ->date('2020-09-13')
            ->get();

        $this->assertEquals(1.504, $result);
    }

    /**
     * @test
     */
    public function dynamic_method_call_fails_if_method_is_not_available()
    {
        $this->expectException(\Exception::class);
        $this->client = $this->successMock();

        (new CurrencyFactory())
            ->convert($this->client)
            ->from($this->from)
            ->to($this->to)
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
            ->convert($this->client)
            ->from($this->from)
            ->to($this->to)
            ->date()
            ->get();
    }
}
