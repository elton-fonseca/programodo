<?php

use App\Exceptions\InstructionException;
use App\Tasks\Instruction\CheckExpirationDate;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2024-01-01 12:00:00');

    $this->checker = app(CheckExpirationDate::class);
    $this->baseInstruction = [
        'expiresAt' => '2024-01-01 12:00:00',
        'amounts' => [],
        'details' => [],
    ];
});

afterEach(function () {
    Carbon::setTestNow();
});

test('passes when expiration date is in the future', function () {
    $instruction = array_merge($this->baseInstruction, [
        'expiresAt' => '2024-12-31 23:59:59',
    ]);

    expect(fn () => $this->checker->execute($instruction))
        ->not->toThrow(InstructionException::class);
});

test('throws exception when expiration date is in the past', function () {
    $instruction = array_merge($this->baseInstruction, [
        'expiresAt' => '2023-12-31 23:59:59',
    ]);

    expect(fn () => $this->checker->execute($instruction))
        ->toThrow(
            InstructionException::class,
            'A data limite de pagamento deste QR Code foi ultrapassada'
        );
});

test('passes when expiration date is exactly now', function () {
    $instruction = array_merge($this->baseInstruction, [
        'expiresAt' => '2024-01-01 12:00:00',
    ]);

    expect(fn () => $this->checker->execute($instruction))
        ->not->toThrow(InstructionException::class);
});
