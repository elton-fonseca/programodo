<?php

namespace App\Actions;

use App\Tasks\Instruction\GetInstruction;
use App\Tasks\Webhook\AddMetadataAppName;
use App\Tasks\Webhook\GetPaymentOrder;

class WebhookProcess
{
    public function __construct(
        private GetInstruction $getInstruction,
        private GetPaymentOrder $getPaymentOrder,
        private AddMetadataAppName $addMetadataAppName
    ) {}

    public function execute(array $data): void
    {
        $instruction = $this->getInstruction->execute($data['instruction']);

        $paymentOrder = $this->getPaymentOrder->execute($instruction);

        $this->addMetadataAppName->execute($instruction, $paymentOrder);
    }
}
