<?php

use App\Enums\PaymentType;
use App\Exceptions\PaymentException;
use App\Services\Payshop\PayshopClientFactoryInterface;
use App\Tasks\Payment\CreatePaymentOrder;
use App\Tasks\Payment\GetPaymentExtraData;
use App\Tasks\Payment\GetPaymentServiceUUID;
use Tests\Doubles\PayshopClientFactoryFake;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->instruction = [
        'gatewayStoreApiKey' => 'test-api-key',
        'gatewayStoreApiSignature' => 'test-signature',
        'guid' => 'test-guid',
        'agreementCode' => 'test-agreement-code',
        'amounts' => [
            ['value' => '1000'],
        ],
    ];

    $this->paymentData = [];
    $this->paymentType = PaymentType::CARD;

    $getPaymentServiceUUID = mock(GetPaymentServiceUUID::class);
    $getPaymentServiceUUID->shouldReceive('execute')
        ->once()
        ->andReturn('test-service-uuid');

    app()->instance(GetPaymentServiceUUID::class, $getPaymentServiceUUID);

    $getPaymentExtraData = mock(GetPaymentExtraData::class);
    $getPaymentExtraData->shouldReceive('execute')
        ->once()
        ->andReturn([]);

    app()->instance(GetPaymentExtraData::class, $getPaymentExtraData);

});

test('creates payment order successfully', function () {
    app()->bind(PayshopClientFactoryInterface::class, PayshopClientFactoryFake::class);
    $this->createPaymentOrder = app(CreatePaymentOrder::class);

    $result = $this->createPaymentOrder->execute(
        $this->instruction,
        $this->paymentData,
        $this->paymentType
    );

    expect($result)
        ->toBeArray()
        ->toHaveKey('token')
        ->and($result['token'])->toBe('valid-token');
});

test('expect to throw an exception when the gateway does not return 200', function () {

    $fakeFactory = new PayshopClientFactoryFake;
    $fakeFactory->setResponseStatus(500);

    app()->instance(PayshopClientFactoryInterface::class, $fakeFactory);

    $this->createPaymentOrder = app(CreatePaymentOrder::class);

    expect(function () {
        $this->createPaymentOrder->execute(
            $this->instruction,
            $this->paymentData,
            $this->paymentType
        );
    })->toThrow(PaymentException::class);
});
