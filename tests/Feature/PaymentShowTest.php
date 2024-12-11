<?php

use Illuminate\Support\Facades\Session;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Session::flush();
});

test('shows payment page when valid instruction is provided', function () {
    $instructionId = 'valid-instruction';

    $expectedInstruction = [
        'agreementCode' => 'TestAgreement',
        'guid' => 'valid-instruction',
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
    ];

    mockPayByLink([
        'instruction' => [
            'id' => 'valid-instruction',
            'status' => 200,
            'data' => $expectedInstruction,
        ],
        'attempts' => [],
        'payments' => [],
    ]);

    $this->get("/link/{$instructionId}")
        ->assertOk()
        ->assertViewIs('payment')
        ->assertViewHas('instruction', $expectedInstruction);

    $this->assertEquals($expectedInstruction, Session::get("instruction-{$instructionId}"));
});

test('Shows error page when the instruction id is invalid', function () {
    $instructionId = 'invalid-instruction';

    mockPayByLink([
        'instruction' => [
            'id' => 'invalid-instruction',
            'status' => 404,
        ],
    ]);

    assertPaymentError(
        test: $this,
        instructionId: $instructionId,
        title: 'Link não encontrado',
        message: 'Verifique o link ou Qr Code utilizado'
    );
});

test('Shows error page when the paybylink doesnt respond 200', function () {
    $instructionId = 'invalid-instruction';

    mockPayByLink([
        'instruction' => [
            'id' => 'invalid-instruction',
            'status' => 501,
            'data' => [''],
        ],
    ]);

    assertPaymentError(
        test: $this,
        instructionId: $instructionId,
        title: 'Pagamento',
        message: 'Lamentamos, mas o serviço está indisponível no momento.<br><br> Por favor, tente novamente mais tarde.'
    );
});

test('Shows error page when the instruction is expirated', function () {
    $instructionId = 'valid-instruction';

    $expectedInstruction = [
        'guid' => 'valid-instruction',
        'expiresAt' => '2024-10-30T10:11:00Z',
    ];

    mockPayByLink([
        'instruction' => [
            'id' => 'valid-instruction',
            'status' => 200,
            'data' => $expectedInstruction,
        ],
    ]);

    assertPaymentError(
        test: $this,
        instructionId: $instructionId,
        title: 'Data limite de Pagamento foi ultrapassada',
        message: 'A data limite de pagamento deste QR Code foi ultrapassada'
    );
});

test('Shows error page when multiple amounts are provided', function () {
    $instructionId = 'valid-instruction';

    $expectedInstruction = [
        'guid' => 'valid-instruction',
        'expiresAt' => Carbon\Carbon::now()->addDay()->toIso8601String(),
        'amounts' => [
            ['value' => 100.00],
            ['value' => 200.00],
        ],
    ];

    mockPayByLink([
        'instruction' => [
            'id' => 'valid-instruction',
            'status' => 200,
            'data' => $expectedInstruction,
        ],
    ]);

    assertPaymentError(
        test: $this,
        instructionId: $instructionId,
        title: 'Erro nas informações de pagamento',
        message: 'Não é possível realizar o pagamento com mais de um valor'
    );
});

test('Shows error page when multiple attempts are provided', function () {
    $instructionId = 'valid-instruction';

    $expectedInstruction = [
        'guid' => 'valid-instruction',
        'expiresAt' => Carbon\Carbon::now()->addDay()->toIso8601String(),
        'amounts' => [
            ['value' => 100.00],
        ],
        'maxPayments' => 2,
    ];

    mockPayByLink([
        'instruction' => [
            'id' => 'valid-instruction',
            'status' => 200,
            'data' => $expectedInstruction,
        ],
    ]);

    assertPaymentError(
        test: $this,
        instructionId: $instructionId,
        title: 'Erro nas informações de pagamento',
        message: 'Não é possível realizar o pagamento com mais de uma tentativa'
    );
});

test('Shows error page when attempt is alread made', function () {
    $instructionId = 'valid-instruction';

    $expectedInstruction = [
        'agreementCode' => 'TestAgreement',
        'guid' => 'valid-instruction',
        'expiresAt' => Carbon\Carbon::now()->addDay()->toIso8601String(),
        'maxPayments' => 1,
        'amounts' => [
            [
                'value' => 18.10,
            ],
        ],
    ];

    mockPayByLink([
        'instruction' => [
            'id' => 'valid-instruction',
            'status' => 200,
            'data' => $expectedInstruction,
        ],
        'attempts' => [1],
    ]);

    assertPaymentError(
        test: $this,
        instructionId: $instructionId,
        title: 'Limite de tentativas excedido',
        message: 'Este link já foi utilizado anteriormente para uma tentativa de pagamento<br><br>Por favor, procure outro meio de pagamento.'
    );
});

test('Shows error page when it was alread paid', function () {
    $instructionId = 'valid-instruction';

    $expectedInstruction = [
        'agreementCode' => 'TestAgreement',
        'guid' => 'valid-instruction',
        'expiresAt' => Carbon\Carbon::now()->addDay()->toIso8601String(),
        'maxPayments' => 1,
        'amounts' => [
            [
                'value' => 18.10,
            ],
        ],
    ];

    mockPayByLink([
        'instruction' => [
            'id' => 'valid-instruction',
            'status' => 200,
            'data' => $expectedInstruction,
        ],
        'attempts' => [],
        'payments' => [1],
    ]);

    assertPaymentError(
        test: $this,
        instructionId: $instructionId,
        title: 'O Link já está pago',
        message: 'O pagamento desse link já foi realizado anteriormente'
    );
});

function assertPaymentError(TestCase $test, string $instructionId, string $title, string $message)
{
    $test->get("/link/{$instructionId}")
        ->assertRedirect(route('error', $instructionId))
        ->assertSessionHas('error', [
            'title' => $title,
            'message' => $message,
        ]);
}
