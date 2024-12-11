<?php

use App\Services\PayByLink\Client as PayByLinkClient;
use Illuminate\Http\Client\Response;

function mockResponse(int $status, array $json = [])
{
    $response = Mockery::mock(Response::class);
    $response->shouldReceive('status')->andReturn($status);
    if (! empty($json)) {
        $response->shouldReceive('json')->andReturn($json);
    }

    return $response;
}

function mockPayByLink(array $config = [])
{
    $client = Mockery::mock(PayByLinkClient::class);

    if (isset($config['instruction'])) {
        $client->shouldReceive('getInstruction')
            ->with($config['instruction']['id'])
            ->once()
            ->andReturn(mockResponse($config['instruction']['status'], $config['instruction']['data'] ?? []));

        if (isset($config['attempts'])) {
            $client->shouldReceive('getInstructionAttempts')
                ->once()
                ->with($config['instruction']['data']['agreementCode'], $config['instruction']['data']['guid'])
                ->andReturn($config['attempts']);
        }

        if (isset($config['payments'])) {
            $client->shouldReceive('getInstructionPayments')
                ->once()
                ->with($config['instruction']['data']['agreementCode'], $config['instruction']['data']['guid'])
                ->andReturn($config['payments']);
        }
    }

    app()->instance(PayByLinkClient::class, $client);

    return $client;
}
