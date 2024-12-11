<?php

namespace Tests\Doubles;

class PayshopClientFake
{
    private int $responseStatus;

    private array $clientServicesResponse = [];

    public function setResponseStatus(int $status): void
    {
        $this->responseStatus = $status;
    }

    public function setClientServicesResponse(array $response): void
    {
        $this->clientServicesResponse = $response;
    }

    public function getRedirectUrl(): string
    {
        return 'https://payment-url.com';
    }

    public function createPaymentOrder(array $orderData): array
    {
        return [
            'status' => $this->responseStatus,
            'response' => [
                'order' => [
                    'token' => 'valid-token',
                ],
            ],
        ];
    }

    public function getClientServices(string $clientUUID): array
    {
        return $this->clientServicesResponse;
    }
}
