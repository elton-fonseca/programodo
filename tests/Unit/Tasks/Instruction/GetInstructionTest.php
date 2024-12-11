<?php

use App\Exceptions\InstructionException;
use App\Services\PayByLink\Client as PayByLinkClient;
use App\Tasks\Instruction\GetInstruction;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

uses(TestCase::class);

test('it should return json data when instruction is found', function () {
    $expectedData = ['key' => 'value'];

    $response = Mockery::mock(Response::class);
    $response->shouldReceive('status')->andReturn(200);
    $response->shouldReceive('json')->andReturn($expectedData);

    $client = Mockery::mock(PayByLinkClient::class);
    $client->shouldReceive('getInstruction')
        ->with('INSTRUCTION_ID')
        ->once()
        ->andReturn($response);

    app()->instance(PayByLinkClient::class, $client);
    $getInstruction = app(GetInstruction::class);

    $result = $getInstruction->execute('INSTRUCTION_ID');

    expect($result)->toBe($expectedData);
});

test('it should throw exception when instruction is not found', function () {
    $response = Mockery::mock(Response::class);
    $response->shouldReceive('status')->andReturn(404);

    $client = Mockery::mock(PayByLinkClient::class);
    $client->shouldReceive('getInstruction')
        ->with('INVALID_INSTRUCTION_ID')
        ->once()
        ->andReturn($response);

    app()->instance(PayByLinkClient::class, $client);
    $getInstruction = app(GetInstruction::class);

    expect(fn () => $getInstruction->execute('INVALID_INSTRUCTION_ID'))
        ->toThrow(InstructionException::class, 'Verifique o link ou Qr Code utilizado');
});

test('it should throw exception when api returns error', function () {
    $response = Mockery::mock(Response::class);
    $response->shouldReceive('status')->andReturn(500);
    $response->shouldReceive('json')->andReturn(['error' => 'server error']);

    $client = Mockery::mock(PayByLinkClient::class);
    $client->shouldReceive('getInstruction')
        ->with('123')
        ->once()
        ->andReturn($response);

    Log::shouldReceive('error')
        ->once()
        ->with('Error on get instruction from PayByLink API', [
            'response' => ['error' => 'server error'],
            'status' => 500,
        ]);

    app()->instance(PayByLinkClient::class, $client);
    $getInstruction = app(GetInstruction::class);

    expect(fn () => $getInstruction->execute('123'))
        ->toThrow(InstructionException::class, 'Lamentamos, mas o serviço está indisponível no momento.');
});

beforeEach(function () {
    $this->fake = Mockery::mock(PayByLinkClient::class);
});

afterEach(function () {
    Mockery::close();
});
