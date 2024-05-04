<?php

namespace App\Integrations;

use App\Interfaces\SMSProvider;
use Psr\Http\Message\ResponseInterface;

class ProductionSMSProvider extends SMSProvider
{
    public function __construct()
    {
        parent::__construct(config('SMS.production_provider'));
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return ['content-type' => 'application/json'];
    }

    /**
     * @param  string $phone
     * @param  string $message
     * @return ResponseInterface
     */
    public function send(string $phone, string $message): ResponseInterface
    {
        return $this->sendRequest('POST', '', ['phone' => $phone, 'message' => $message]);
    }
}
