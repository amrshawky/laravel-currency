<?php

namespace AmrShawky\Currency\Traits;

use AmrShawky\Currency\Exceptions\RequestException;
use GuzzleHttp\Client;

trait HttpRequest
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \GuzzleHttp\Psr7\Response
     */
    protected $response;

    /**
     * @var
     */
    protected $throw;

    /**
     * @var
     */
    protected $throw_callback;

    /**
     * @var array
     */
    protected $options = [
        'http_errors' => false
    ];

    /**
     * @param string $method
     * @param string $url
     * @param array  $params
     *
     * @return null|mixed
     */
    protected function request(string $url, array $params = [], string $method = 'GET')
    {
        try {
            $this->initClient();

            $this->response = $this->client->request($method, $url, [
                'query'=> $params
            ]);

            if ($this->failed()) {
                throw new RequestException($this->response);
            }

            $response = json_decode(
                $this->response->getBody()->getContents()
            );

            if (empty($response->success) || $response->success === false) {
                throw new RequestException($this->response);
            }

            return $response;

        } catch (\Exception $e) {
            if ($this->throw) {
                if ($this->throw_callback && is_callable($this->throw_callback)) {
                    call_user_func($this->throw_callback, $this->response, $e);
                }
                throw $e;
            }

            return null;
        }
    }

    protected function initClient()
    {
        if (!$this->client) {
            $this->client = new Client($this->options);
        } else {
            $this->client = new Client(
                array_replace_recursive($this->client->getConfig(), $this->options)
            );
        }
    }

    /**
     * Indicate that TLS certificates should not be verified.
     *
     * @return $this
     */
    public function withoutVerifying()
    {
        $this->options['verify'] = false;

        return $this;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function withOptions(array $options)
    {
        $this->options = array_merge_recursive($this->options, $options);

        return $this;
    }

    /**
     * @param callable|null $calback
     *
     * @return $this
     */
    public function throw(?callable $callback = null)
    {
        $this->throw = true;

        if ($callback) {
            $this->throw_callback = $callback;
        }

        return $this;
    }

    /**
     * @return bool
     */
    protected function failed()
    {
        return $this->response->getStatusCode() >= 400;
    }

}