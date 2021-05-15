<?php

namespace AmrShawky\LaravelCurrency\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

trait ClientMock
{
    private $client;

    private function mock(array $params)
    {
        $mock           = new MockHandler($params);
        $handlerStack   = HandlerStack::create($mock);
        return new Client([
            'handler'     => $handlerStack,
            'http_errors' => false
        ]);
    }
}