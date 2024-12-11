<?php

namespace App\Tasks\Webhook;

use App\Services\Payshop\PayshopClientFactoryInterface;

class GetPaymentOrder
{
    public function __construct(
        private PayshopClientFactoryInterface $payshopFactory
    ) {}

    public function execute(array $instruction): array
    {
        $payshopClient = $this->payshopFactory->make(
            $instruction['gatewayStoreApiKey'],
            $instruction['gatewayStoreApiSignature'],
        );

        $response = $payshopClient->getPaymentOrder(
            $this->getPaymentOrderId($instruction)
        );

        if ($response['status'] != '200') {
            throw new \Exception('Error getting payment order');
        }

        return $response['response']['order'];
    }

    private function getPaymentOrderId(array $instruction): string
    {
        return collect($instruction['details'])
            ->where('name', 'payment_order_uuid')
            ->first()['value'];
    }
}
