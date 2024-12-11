<?php

namespace App\Tasks\Payment;

use App\Enums\PaymentType;
use App\Services\Payshop\PayshopClientFactoryInterface;

class GetRedirectLink
{
    public function __construct(
        private PayshopClientFactoryInterface $payshopFactory
    ) {}

    public function execute(array $instruction, string $orderToken, PaymentType $paymentType): string
    {
        $payshopClient = $this->payshopFactory->make(
            $instruction['gatewayStoreApiKey'],
            $instruction['gatewayStoreApiSignature'],
        );

        $redirectLink = $payshopClient->getRedirectUrl($orderToken);

        return $this->addLinkPaymentType($redirectLink, $paymentType);
    }

    private function addLinkPaymentType(string $redirectLink, PaymentType $paymentType): string
    {
        if ($paymentType == PaymentType::MBWAY || $paymentType == PaymentType::MULTIBANCO) {
            return $redirectLink.'?apm='.strtoupper($paymentType->value);
        }

        return $redirectLink;
    }
}
