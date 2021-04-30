<?php

namespace AmrShawky\Currency\Exceptions;

use Exception;
use GuzzleHttp\Psr7\Response;

class RequestException extends Exception
{
    /**
     * @var \GuzzleHttp\Psr7\Response
     */
    public $response;

    /**
     * RequestException constructor.
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        parent::__construct($response->getBody(), $response->getStatusCode());

        $this->response = $response;
    }
}