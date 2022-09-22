<?php

namespace DigitalUnion\utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Request
{
    /**
     * @var Object http client to call service api
     */
    public $httpClient;

    /**
     *
     */
    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * @param $method string
     * @param $url string
     * @param $header array
     * @param $body string
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function transfer($method, $url, $header, $body, $runtimeMode)
    {
        $options = [];

        $headers = [];
        $headers[contentType] = contentTypeJson;
        $headers[sdkVerKey] = $runtimeMode;
        foreach ($header as $key => $val) {
            $headers[$key] = $val;
        }
        $options['headers'] = $headers;

        if (strlen($body) > 0) {
            $options['body'] = $body;
        }

        return $this->httpClient->request($method, $url, $options);
    }
}
