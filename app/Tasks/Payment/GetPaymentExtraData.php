<?php

namespace App\Tasks\Payment;

use App\Enums\PaymentType;

class GetPaymentExtraData
{
    public function execute(PaymentType $paymentType, array $paymentData): array
    {
        if ($paymentType == PaymentType::MBWAY) {
            return $this->mbWayData($paymentData);
        }

        if ($paymentType == PaymentType::CARD) {
            return $this->cardData();
        }

        return [
            'secure' => false,
        ];
    }

    private function mbWayData(array $paymentData): array
    {
        return [
            'extra_data' => [
                'profile' => [
                    'first_name' => 'PayByLink',
                    'last_name' => 'PayByLink',
                    'phone' => [
                        'number' => $paymentData['phone'],
                        'prefix' => $paymentData['prefix'],
                    ],
                ],
            ],
        ];
    }

    private function cardData(): array
    {
        return [
            'secure' => true,
            'save_card' => false,
        ];
    }
}
