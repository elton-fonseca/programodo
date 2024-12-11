<?php

use App\Enums\PaymentType;
use App\Tasks\Payment\GetPaymentExtraData;
use Tests\TestCase;

uses(TestCase::class);

test('returns mbway data when payment type is MBWAY', function () {
    $paymentType = PaymentType::MBWAY;
    $paymentData = [
        'phone' => '123456789',
        'prefix' => '351',
    ];

    $task = app(GetPaymentExtraData::class);
    $result = $task->execute($paymentType, $paymentData);

    expect($result)->toBe([
        'extra_data' => [
            'profile' => [
                'first_name' => 'PayByLink',
                'last_name' => 'PayByLink',
                'phone' => [
                    'number' => '123456789',
                    'prefix' => '351',
                ],
            ],
        ],
    ]);
});

test('returns empty array when payment type is not MBWAY', function () {
    $paymentType = PaymentType::CARD;
    $paymentData = [];

    $task = app(GetPaymentExtraData::class);
    $result = $task->execute($paymentType, $paymentData);

    expect($result)->toBe([]);
});
