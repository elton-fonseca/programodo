<?php

use App\Enums\PaymentType;
use App\Services\Payshop\PayshopClientFactoryInterface;
use Illuminate\Support\Facades\Session;
use Tests\Doubles\PayshopClientFactoryFake;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Session::flush();
});

test('sucess', function () {
    app()->bind(PayshopClientFactoryInterface::class, PayshopClientFactoryFake::class);

    $instructionId = 'valid-id';

    $expectedInstruction = [
        'agreementCode' => 'TestAgreement',
        'guid' => $instructionId,
        'expiresAt' => Carbon\Carbon::now()->addDay()->toIso8601String(),
        'maxPayments' => 1,
        'amounts' => [
            [
                'value' => 18.10,
                'paymentMethods' => [
                    'card',
                ],
            ],
        ],
        'details' => [],
        'gatewayStoreApiKey' => 'test',
        'gatewayStoreApiSignature' => 'test',
        'gatewayStoreClientUUID' => 'test',
    ];

    Session::start();
    Session::put("instruction-{$instructionId}", $expectedInstruction);

    $this->post('/link/process', [
        'instruction_id' => $instructionId,
        'payment_type' => PaymentType::PAYSHOP->value,
    ])
        ->assertRedirect('https://payment-url.com');
});

test('404', function () {
    $this->post('/link/process', [
        'instruction_id' => 'valid-id',
        'payment_type' => PaymentType::PAYSHOP->value,
    ])
        ->assertStatus(404);
});

test('sucess mbway', function () {
    $factory = new PayshopClientFactoryFake;
    $factory->mockClientServicesResponse([
        'status' => 200,
        'response' => [
            'services' => [
                [
                    'type' => config('services.payshop.payment_services.'.PaymentType::MBWAY->value),
                    'uuid' => 'test-service-uuid',
                ],
            ],
        ],
    ]);

    app()->instance(PayshopClientFactoryInterface::class, $factory);

    $instructionId = 'valid-id';

    $expectedInstruction = [
        'agreementCode' => 'TestAgreement',
        'guid' => $instructionId,
        'expiresAt' => Carbon\Carbon::now()->addDay()->toIso8601String(),
        'maxPayments' => 1,
        'amounts' => [
            [
                'value' => 18.10,
                'paymentMethods' => [
                    'mbway',
                ],
            ],
        ],
        'details' => [],
        'gatewayStoreApiKey' => 'test',
        'gatewayStoreApiSignature' => 'test',
        'gatewayStoreClientUUID' => 'test',
    ];

    Session::start();
    Session::put("instruction-{$instructionId}", $expectedInstruction);

    $this->post('/link/process', [
        'instruction_id' => $instructionId,
        'payment_type' => PaymentType::MBWAY->value,
        'prefix' => '123',
        'phone' => '123456789',
    ])
        ->assertRedirect('https://payment-url.com?apm=MBWAY');
});
