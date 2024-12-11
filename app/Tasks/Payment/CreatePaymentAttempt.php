<?php

namespace App\Tasks\Payment;

use App\Services\PayByLink\Client as PayByLinkClient;

class CreatePaymentAttempt
{
    public function __construct(
        private PayByLinkClient $payByLinkApiClient
    ) {}

    public function execute(string $paymentOrderId): void
    {
        $data = [
            'order' => [
                'uuid' => $paymentOrderId,
            ],
        ];

        $this->payByLinkApiClient->createAttempt($data);
    }
}
