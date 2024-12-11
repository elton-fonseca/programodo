<?php

declare(strict_types=1);

namespace App\Tasks\Wallet;

use App\Enums\PaymentType;
use App\Services\Payshop\PayshopClientFactoryInterface;

class SendWalletPayment
{
    public function __construct(
        private PayshopClientFactoryInterface $payshopFactory
    ) {}

    public function execute(array $instruction, string $paymentOrderId, array $payload, PaymentType $paymentType)
    {
        $data = [
            'order_uuid' => $paymentOrderId,
            'wallet' => strtoupper($paymentType->value),
            'payload' => $payload,
            'customer_ip' => request()->ip(),
            'flow' => 'WEB',
        ];

        $payshopClient = $this->payshopFactory->make(
            $instruction['gatewayStoreApiKey'],
            $instruction['gatewayStoreApiSignature'],
        );

        return $payshopClient->paymentWallet($data);
    }
}
