<?php

namespace App\Tasks\Instruction;

use App\Exceptions\InstructionException;
use App\Services\PayByLink\Client as PayByLinkClient;

class CreateInstruction
{
    public function __construct(
        private PayByLinkClient $payByLinkApiClient
    ) {}

    public function execute(array $data): string
    {
        $agreementCode = $this->getAgreementCode($data['billIssuerID']);
        unset($data['billIssuerID']);

        $response = $this->payByLinkApiClient->createInstruction($agreementCode, $data);

        if ($response->failed()) {
            throw new InstructionException(
                title: 'Não foi possível criar o pagamento',
                message: 'Os dados enviados no código CB48 são inválidos',
            );
        }

        return $response->json('guid');
    }

    public function getAgreementCode(string $billIssuerID): string
    {
        $response = $this->payByLinkApiClient->getAgreement($billIssuerID);

        if ($response->failed()) {
            throw new InstructionException(
                title: 'Código do emissor não encontrado',
                message: 'O código do emissor informado no CB48 é invalido',
            );
        }

        return $response->json('agreementCode');
    }
}
