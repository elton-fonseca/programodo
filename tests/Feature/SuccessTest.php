<?php

use Illuminate\Support\Facades\Session;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Session::flush();
});

test('shows success page when valid instruction is provided', function () {
    $instruction = 'valid-instruction';
    $expectedInstruction = [
        'amounts' => [
            [
                'value' => 150.00,
            ],
        ],
        'expiresAt' => '2024-03-25 23:59:59',
        'details' => [],
    ];

    Session::put("instruction-{$instruction}", $expectedInstruction);

    $this->get("/success/{$instruction}")
        ->assertOk()
        ->assertViewIs('success')
        ->assertViewHas('instruction', $expectedInstruction);
});

test('redirects to error page when instruction is not found', function () {
    $instruction = 'invalid-instruction';

    $this->get("/success/{$instruction}")
        ->assertRedirect(route('error'))
        ->assertSessionHas('error', [
            'title' => 'Link não encontrado',
            'message' => 'Verifique o link ou Qr Code utilizado',
        ]);
});
