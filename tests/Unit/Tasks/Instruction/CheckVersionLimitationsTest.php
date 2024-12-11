<?php

use App\Exceptions\InstructionException;
use App\Tasks\Instruction\CheckVersionLimitations;

test('passes validation with single amount and attempt', function () {
    $instruction = [
        'amounts' => [
            ['value' => 100.00],
        ],
        'maxPayments' => 1,
    ];

    $checker = app(CheckVersionLimitations::class);

    expect(fn () => $checker->execute($instruction))
        ->not
        ->toThrow(InstructionException::class);
});

test('throws exception when multiple amounts are provided', function () {
    $instruction = [
        'amounts' => [
            ['value' => 100.00],
            ['value' => 200.00],
        ],
        'maxPayments' => 1,
    ];

    $checker = app(CheckVersionLimitations::class);

    expect(fn () => $checker->execute($instruction))
        ->toThrow(InstructionException::class, 'Não é possível realizar o pagamento com mais de um valor');
});

test('throws exception when multiple attempts are provided', function () {
    $instruction = [
        'amounts' => [
            ['value' => 100.00],
        ],
        'maxPayments' => 2,
    ];

    $checker = app(CheckVersionLimitations::class);

    expect(fn () => $checker->execute($instruction))
        ->toThrow(InstructionException::class, 'Não é possível realizar o pagamento com mais de uma tentativa');
});

test('throws exception with both multiple amounts and attempts', function () {
    $instruction = [
        'amounts' => [
            ['value' => 100.00],
            ['value' => 200.00],
        ],
        'maxPayments' => 2,
    ];

    $checker = app(CheckVersionLimitations::class);

    expect(fn () => $checker->execute($instruction))
        ->toThrow(InstructionException::class, 'Não é possível realizar o pagamento com mais de um valor');
});
