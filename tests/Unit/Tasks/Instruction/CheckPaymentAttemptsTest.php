<?php

use App\Exceptions\InstructionException;
use App\Services\PayByLink\Client as PayByLinkClient;
use App\Tasks\Instruction\CheckPaymentAttempts;

beforeEach(function () {
    $this->payByLinkClient = Mockery::mock(PayByLinkClient::class);
    app()->instance(PayByLinkClient::class, $this->payByLinkClient);
    $this->checkPaymentAttempts = app(CheckPaymentAttempts::class);

    $this->instruction = [
        'agreementCode' => 'ABC123',
        'guid' => '550e8400-e29b-41d4-a716-446655440000',
        'maxPayments' => 1,
    ];
});

test('executes successfully when attempts are less than max payments', function () {
    $this->payByLinkClient
        ->shouldReceive('getInstructionAttempts')
        ->once()
        ->with($this->instruction['agreementCode'], $this->instruction['guid'])
        ->andReturn([]);

    expect(fn () => $this->checkPaymentAttempts->execute($this->instruction))
        ->not->toThrow(InstructionException::class);
});

test('throws exception when attempts equal or exceed max payments', function () {
    $this->payByLinkClient
        ->shouldReceive('getInstructionAttempts')
        ->once()
        ->with($this->instruction['agreementCode'], $this->instruction['guid'])
        ->andReturn([1]);

    $expectedMessage = 'Este link já foi utilizado anteriormente para uma tentativa de pagamento<br><br>Por favor, procure outro meio de pagamento.';

    expect(fn () => $this->checkPaymentAttempts->execute($this->instruction))
        ->toThrow(InstructionException::class, $expectedMessage);
});

afterEach(function () {
    Mockery::close();
});
