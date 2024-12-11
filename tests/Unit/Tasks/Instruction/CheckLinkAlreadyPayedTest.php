<?php

use App\Exceptions\InstructionException;
use App\Services\PayByLink\Client as PayByLinkClient;
use App\Tasks\Instruction\CheckLinkAlreadyPayed;

beforeEach(function () {
    $this->payByLinkClient = Mockery::mock(PayByLinkClient::class);
    app()->instance(PayByLinkClient::class, $this->payByLinkClient);
    $this->checkPaymentAttempts = app(CheckLinkAlreadyPayed::class);

    $this->instruction = [
        'agreementCode' => 'ABC123',
        'guid' => '550e8400-e29b-41d4-a716-446655440000',
    ];
});

test('executes successfully when no payments exist', function () {

    $this->payByLinkClient
        ->shouldReceive('getInstructionPayments')
        ->once()
        ->with($this->instruction['agreementCode'], $this->instruction['guid'])
        ->andReturn([]);

    $task = new CheckLinkAlreadyPayed($this->payByLinkClient);

    expect(fn () => $task->execute($this->instruction))->not->toThrow(InstructionException::class);
});

test('throws exception when payments exist', function () {

    $this->payByLinkClient
        ->shouldReceive('getInstructionPayments')
        ->once()
        ->with($this->instruction['agreementCode'], $this->instruction['guid'])
        ->andReturn([
            ['id' => 1, 'status' => 'paid'],
        ]);

    $task = new CheckLinkAlreadyPayed($this->payByLinkClient);

    expect(fn () => $task->execute($this->instruction))
        ->toThrow(InstructionException::class, 'O pagamento desse link já foi realizado anteriormente');
});

afterEach(function () {
    Mockery::close();
});
