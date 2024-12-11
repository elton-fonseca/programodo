<?php

declare(strict_types=1);

namespace App\Tasks\Instruction;

use App\Exceptions\InstructionException;
use App\Services\PayByLink\Client as PayByLinkClient;

class CheckLinkAlreadyPayed
{
    public function __construct(
        private PayByLinkClient $payByLinkApiClient
    ) {}

    public function execute(array $instruction): void
    {
        $payments = $this->payByLinkApiClient->getInstructionPayments(
            $instruction['agreementCode'],
            $instruction['guid']
        );

        if (count($payments) > 0) {
            throw new InstructionException(
                title: 'O Link já está pago',
                message: 'O pagamento desse link já foi realizado anteriormente'
            );
        }
    }
}
