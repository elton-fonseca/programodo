<?php

use App\Enums\PaymentType;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Session::flush();
});

test('shows the card option when its avalable', function () {
    $instructionId = 'valid-instruction';
    $expectedInstruction = getExpectedInstructionWithDifferentMethods([
        pbl_payment_name(PaymentType::CARD),
    ]);

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
        ->assertViewHas('instruction', $expectedInstruction)
        ->assertSeeText(payment_visible_name(PaymentType::CARD));
});

test('does not show the card option when is not avalable', function () {
    $instructionId = 'valid-instruction';
    $expectedInstruction = getExpectedInstructionWithDifferentMethods();

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
        ->assertViewHas('instruction', $expectedInstruction)
        ->assertDontSeeText(payment_visible_name(PaymentType::CARD));
});

test('shows the mbway option when its avalable', function () {
    $instructionId = 'valid-instruction';
    $expectedInstruction = getExpectedInstructionWithDifferentMethods([
        pbl_payment_name(PaymentType::MBWAY),
    ]);

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
        ->assertViewHas('instruction', $expectedInstruction)
        ->assertSeeText(payment_visible_name(PaymentType::MBWAY));
});

test('does not show the mbway option when is not avalable', function () {
    $instructionId = 'valid-instruction';
    $expectedInstruction = getExpectedInstructionWithDifferentMethods();

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
        ->assertViewHas('instruction', $expectedInstruction)
        ->assertDontSeeText(payment_visible_name(PaymentType::MBWAY));
});

test('shows the payshop reference option when its avalable', function () {
    $instructionId = 'valid-instruction';
    $expectedInstruction = getExpectedInstructionWithDifferentMethods([
        pbl_payment_name(PaymentType::PAYSHOP),
    ]);

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
        ->assertViewHas('instruction', $expectedInstruction)
        ->assertSeeText(payment_visible_name(PaymentType::PAYSHOP));
});

test('does not show the payshop reference option when is not avalable', function () {
    $instructionId = 'valid-instruction';
    $expectedInstruction = getExpectedInstructionWithDifferentMethods();

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
        ->assertViewHas('instruction', $expectedInstruction)
        ->assertDontSeeText(payment_visible_name(PaymentType::PAYSHOP));
});

test('shows the multibanco reference option when its avalable', function () {
    $instructionId = 'valid-instruction';
    $expectedInstruction = getExpectedInstructionWithDifferentMethods([
        pbl_payment_name(PaymentType::MULTIBANCO),
    ]);

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
        ->assertViewHas('instruction', $expectedInstruction)
        ->assertSeeText(payment_visible_name(PaymentType::MULTIBANCO));
});

test('does not show the multibanco reference option when is not avalable', function () {
    $instructionId = 'valid-instruction';
    $expectedInstruction = getExpectedInstructionWithDifferentMethods();

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
        ->assertViewHas('instruction', $expectedInstruction)
        ->assertDontSeeText(payment_visible_name(PaymentType::MULTIBANCO));
});

function getExpectedInstructionWithDifferentMethods(array $paymentMethods = []): array
{
    return [
        'agreementCode' => 'TestAgreement',
        'guid' => 'valid-instruction',
        'expiresAt' => Carbon\Carbon::now()->addDay()->toIso8601String(),
        'maxPayments' => 1,
        'amounts' => [
            [
                'value' => 18.10,
                'paymentMethods' => $paymentMethods,
            ],
        ],
        'details' => [],
    ];
}
