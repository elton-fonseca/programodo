<?php

namespace App\Tasks\Payment;

use App\Enums\PaymentType;
use App\Exceptions\PaymentException;
use App\Services\Payshop\PayshopClientFactoryInterface;
use Cknow\Money\Money;

class CreatePaymentOrder
{
    private array $instruction;

    private array $paymentData;

    private PaymentType $paymentType;

    private string $paymentServiceUUID;

    public function __construct(
        private GetPaymentServiceUUID $getPaymentServiceUUID,
        private GetPaymentExtraData $getPaymentExtraData,
        private PayshopClientFactoryInterface $payshopFactory
    ) {}

    public function execute(array $instruction, array $paymentData, PaymentType $paymentType): array
    {
        $this->instruction = $instruction;
        $this->paymentData = $paymentData;
        $this->paymentType = $paymentType;

        $this->paymentServiceUUID = $this->getPaymentServiceUUID->execute($instruction, $paymentType);

        return $this->createOrder();
    }

    private function createOrder(): array
    {
        $payshopClient = $this->payshopFactory->make(
            $this->instruction['gatewayStoreApiKey'],
            $this->instruction['gatewayStoreApiSignature'],
        );

        $orderData = $this->getOrderData();

        $response = $payshopClient->createPaymentOrder($orderData);

        if ($response['status'] !== 200) {
            throw new PaymentException(
                message: 'Erro ao criar pedido de pagamento no gateway',
                title: 'Erro ao criar pedido de pagamento no gateway'
            );
        }

        return $response['response']['order'];
    }

    private function getOrderData(): array
    {
        $data = [
            'operative' => 'AUTHORIZATION',
            'service' => $this->paymentServiceUUID,
            'amount' => $this->getAmount(),
            'description' => '',
            'url_ok' => route('success', $this->instruction['guid']),
            'url_ko' => route('error', $this->instruction['guid']),
            'url_post' => config('services.paybylink.switch_events_url'),
            'additional' => json_encode([
                'pbl_instruction_guid' => $this->instruction['guid'],
                'pbl_agreement_code' => $this->instruction['agreementCode'],
            ]),
        ];

        return $data + $this->getPaymentExtraData->execute($this->paymentType, $this->paymentData);
    }

    private function getAmount(): int
    {
        $amount = (string) $this->instruction['amounts'][0]['value'];

        return (int) Money::parse($amount, 'EUR', true)->getAmount();
    }
}
