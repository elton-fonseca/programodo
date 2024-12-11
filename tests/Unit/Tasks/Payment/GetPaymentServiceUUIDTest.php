<?php

use App\Enums\PaymentType;
use App\Exceptions\PaymentException;
use App\Tasks\Payment\GetPaymentServiceUUID;
use Tests\Doubles\PayshopClientFactoryFake;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->factory = new PayshopClientFactoryFake;
    $this->task = new GetPaymentServiceUUID($this->factory);
    $this->instruction = [
        'gatewayStoreApiKey' => 'test-key',
        'gatewayStoreApiSignature' => 'test-signature',
        'gatewayStoreClientUUID' => 'client-uuid',
    ];
});

test('returns correct uuid for valid payment type', function () {
    $expectedUuid = 'test-service-uuid';

    $result = $this->task->execute($this->instruction, PaymentType::PAYSHOP);

    expect($result)->toBe($expectedUuid);
});

test('throws exception when api response status is not 200', function () {
    $this->factory->mockClientServicesResponse([
        'status' => 400,
        'response' => [],
    ]);

    expect(fn () => $this->task->execute($this->instruction, PaymentType::PAYSHOP))
        ->toThrow(PaymentException::class, 'Erro ao obter informações dos serviços de pagamento, tente novamente mais tarde');
});

test('throws exception when service not found for payment type', function () {
    $this->factory->mockClientServicesResponse([
        'status' => 200,
        'response' => [
            'services' => [
                [
                    'type' => 'OTHER_SERVICE',
                    'uuid' => 'other-uuid',
                ],
            ],
        ],
    ]);

    expect(fn () => $this->task->execute($this->instruction, PaymentType::PAYSHOP))
        ->toThrow(PaymentException::class, 'Serviço de pagamento não encontrado no gateway');
});
