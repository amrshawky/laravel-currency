<?php

namespace AmrShawky\Currency\Tests;

use AmrShawky\Currency\CurrencyConversion;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class CurrencyConversionTest extends TestCase
{
    public $from = 'USD';

    public $to   = 'EUR';

    private $client;

    public function setUp(): void
    {
        parent::setUp();
    }

    private function mock(array $params)
    {
        $mock           = new MockHandler($params);
        $handlerStack   = HandlerStack::create($mock);
        return new Client(['handler' => $handlerStack]);
    }

    private function successMock()
    {
        return $this->mock([
            new Response(200, [], json_encode(['success' => true, 'result' => 1.504]))
        ]);
    }

    private function convert()
    {
        return (new CurrencyConversion($this->client))
            ->from($this->from)
            ->to($this->to)
            ->amount(1)
            ->get();
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

        $this->assertNull($this->convert());
        $this->assertNull($this->convert());
        $this->assertNull($this->convert());
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
    public function it_throws_exceptions_when_base_currency_is_missing()
    {
        $this->expectException(\Exception::class);

        $this->client = $this->successMock();

        (new CurrencyConversion($this->client))
            ->to($this->to)
            ->amount(1)
            ->get();

    }

    /**
     * @test
     */
    public function it_throws_exceptions_when_target_currency_is_missing()
    {
        $this->expectException(\Exception::class);

        $this->client = $this->successMock();

        (new CurrencyConversion($this->client))
            ->from($this->from)
            ->amount(1)
            ->get();

    }

    /**
     * @test
     */
    public function it_throws_exceptions_when_amount_is_missing()
    {
        $this->expectException(\Exception::class);

        $this->client = $this->successMock();

        (new CurrencyConversion($this->client))
            ->from($this->from)
            ->to($this->to)
            ->get();
    }
}
