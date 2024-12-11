<?php

namespace App\Tasks\Payment;

use App\Services\PayByLink\Client as PayByLinkClient;

class AddOrderIdMetadata
{
    public function __construct(
        private PayByLinkClient $payByLinkApiClient
    ) {}

    public function execute(string $paymentOrderId, array $instruction): void
    {
        $detail = [
            'name' => 'payment_order_uuid',
            'type' => 'string',
            'value' => $paymentOrderId,
            'showToUser' => false,
            'labelToUser' => 'código ordem de pagamento',
        ];

        $this->payByLinkApiClient->createInstructionDetail(
            $instruction['agreementCode'],
            $instruction['guid'],
            $detail
        );
    }
}
