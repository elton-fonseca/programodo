<?php

use Tests\TestCase;

uses(TestCase::class);

test('error page loads successfully', function () {
    $this->get('/error')
        ->assertStatus(200)
        ->assertViewIs('error');
});

test('error page shows default values when no session data', function () {
    $this->get('/error')
        ->assertSee('Pagamento não efetuado')
        ->assertSee('Erro ao realizar o pagamento');
});

test('error page displays custom session messages', function () {
    session([
        'error.title' => 'Custom Error Title',
        'error.message' => 'Custom Error Message',
    ]);

    $this->get('/error')
        ->assertSee('Custom Error Title')
        ->assertSee('Custom Error Message');
});

test('error page accepts instruction parameter', function () {
    $this->get('/error/custom-instruction')
        ->assertStatus(200)
        ->assertViewHas('instruction');
});

test('error page renders alert image', function () {
    $this->get('/error')
        ->assertSee('images/alert.png')
        ->assertSee('Icone confirmação de pagamento');
});

test('error page uses layout component', function () {
    $this->get('/error')
        ->assertViewHas('instruction');
});
