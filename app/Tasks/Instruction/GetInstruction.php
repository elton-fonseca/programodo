<?php

declare(strict_types=1);

namespace App\Tasks\Instruction;

use App\Exceptions\InstructionException;
use App\Services\PayByLink\Client as PayByLinkClient;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class GetInstruction
{
    public function __construct(
        private PayByLinkClient $payByLinkApiClient
    ) {}

    public function execute(string $instructionId): array
    {
        $response = $this->payByLinkApiClient->getInstruction($instructionId);

        $this->validateNotFound($response);
        $this->validateErrorResponse($response);

        return $response->json();
    }

    private function validateNotFound(Response $response): void
    {
        if ($response->status() === 404) {
            throw new InstructionException(
                message: 'Verifique o link ou Qr Code utilizado',
                title: 'Link não encontrado'
            );
        }
    }

    private function validateErrorResponse(Response $response): void
    {
        if ($response->status() !== 200) {
            Log::error('Error on get instruction from PayByLink API', [
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            throw new InstructionException(
                message: 'Lamentamos, mas o serviço está indisponível no momento.<br><br> Por favor, tente novamente mais tarde.',
                title: 'Pagamento'
            );
        }
    }
}
