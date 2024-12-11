<?php

namespace Tests\Doubles;

use App\Enums\PaymentType;
use App\Services\Payshop\PayshopClientFactoryInterface;

class PayshopClientFactoryFake implements PayshopClientFactoryInterface
{
    private int $responseStatus = 200;

    private array $clientServicesResponse = [];

    public function make(string $apiKey, string $signature): PayshopClientFake
    {
        $instance = new PayshopClientFake;
        $instance->setResponseStatus($this->responseStatus);

        if ($this->clientServicesResponse) {
            $instance->setClientServicesResponse($this->clientServicesResponse);
        } else {
            $instance->setClientServicesResponse([
                'status' => 200,
                'response' => [
                    'services' => [
                        [
                            'type' => config('services.payshop.payment_services.'.PaymentType::PAYSHOP->value),
                            'uuid' => 'test-service-uuid',
                        ],
                    ],
                ],
            ]);
        }

        return $instance;
    }

    public function setResponseStatus(int $status): void
    {
        $this->responseStatus = $status;
    }

    public function mockClientServicesResponse(array $response): void
    {
        $this->clientServicesResponse = $response;
    }
}
