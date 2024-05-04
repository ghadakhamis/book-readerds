<?php

namespace App\Interfaces;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class SMSProvider
{
    protected $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function sendRequest($method, $route, $data = []): ResponseInterface
    {
        $body = [
            'headers'     => $this->getHeaders(),
            'json'        => $data,
            'http_errors' => false
        ];
        
        $client = new Client(['base_uri' => $this->baseUrl]);
        $response= $client->request($method, $route, $body);
        
        return  $response;
    }

    /**
     * @return array
     */
    abstract public function getHeaders(): array;

    /**
     * @param  string $phone
     * @param  string $message
     * @return ResponseInterface
     */
    abstract public function send(string $phone, string $message): ResponseInterface;
}
