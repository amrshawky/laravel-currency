<?php

namespace AmrShawky\Currency\Traits;

use GuzzleHttp\Client;

trait HttpRequest
{
    /**
     * @param Client $client
     * @param string $method
     * @param string $url
     * @param array  $params
     *
     * @return null|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(Client $client, string $url, array $params = [], string $method = 'GET')
    {
        try {
            $response = $client->request($method, $url, [
                'query'=> $params
            ]);
        } catch (\Exception $e) {
            return null;
        }

        if ($response->getStatusCode() >= 400) {
            return null;
        }

        $response = json_decode(
            $response->getBody()->getContents()
        );

        if ($response->success === false) {
            return null;
        }

        return $response;
    }
}