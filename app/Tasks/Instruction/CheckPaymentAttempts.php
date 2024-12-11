<?php

declare(strict_types=1);

namespace App\Tasks\Instruction;

use App\Exceptions\InstructionException;
use App\Services\PayByLink\Client as PayByLinkClient;

class CheckPaymentAttempts
{
    public function __construct(
        private PayByLinkClient $payByLinkApiClient
    ) {}

    public function execute(array $instruction): void
    {
        $attempts = $this->payByLinkApiClient->getInstructionAttempts(
            $instruction['agreementCode'],
            $instruction['guid']
        );

        $message = 'Este link já foi utilizado anteriormente para uma tentativa de pagamento<br><br>';
        $message .= 'Por favor, procure outro meio de pagamento.';

        if (count($attempts) >= $instruction['maxPayments']) {
            throw new InstructionException(
                title: 'Limite de tentativas excedido',
                message: $message
            );
        }
    }
}
