<?php

namespace App\Tasks\Webhook;

use App\Services\Payshop\PayshopClientFactoryInterface;

class AddMetadataAppName
{
    public function __construct(
        private PayshopClientFactoryInterface $payshopFactory
    ) {}

    public function execute(array $instruction, array $paymentOrder): void
    {
        $lastTransaction = $this->getLastTransaction($paymentOrder);

        if (! $lastTransaction || ! isset($lastTransaction['uuid'])) {
            throw new \Exception('Error getting transaction');
        }

        $this->addTransactionMetadata($lastTransaction['uuid'], $instruction);
    }

    private function getLastTransaction(array $paymentOrder): ?array
    {
        $transactions = $paymentOrder['transactions'] ?? [];

        return $transactions ? $transactions[count($transactions) - 1] : null;
    }

    private function addTransactionMetadata(string $transactionUuid, array $instruction): void
    {
        $payshopClient = $this->payshopFactory->make(
            $instruction['gatewayStoreApiKey'],
            $instruction['gatewayStoreApiSignature'],
        );

        $payshopClient->createTransationMetadata(
            $transactionUuid,
            [
                'metadata' => [
                    'source_application' => 'pay_by_link',
                    'pbl_instruction_guid' => $instruction['guid'],
                    'pbl_agreement_code' => $instruction['agreementCode'],
                ],
            ]
        );
    }
}
