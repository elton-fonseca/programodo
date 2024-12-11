<?php

declare(strict_types=1);

namespace App\Tasks\Instruction;

use App\Exceptions\InstructionException;

class CheckVersionLimitations
{
    public function execute(array $instruction): void
    {
        $this->validateQuantityOfAmounts($instruction['amounts']);
        $this->validateQuantityOfAttempts($instruction['maxPayments']);
    }

    private function validateQuantityOfAmounts(array $amounts): void
    {
        if (count($amounts) > 1) {
            throw new InstructionException(
                title: 'Erro nas informações de pagamento',
                message: 'Não é possível realizar o pagamento com mais de um valor'
            );
        }
    }

    private function validateQuantityOfAttempts(int $maxPayments): void
    {
        if ($maxPayments > 1) {
            throw new InstructionException(
                title: 'Erro nas informações de pagamento',
                message: 'Não é possível realizar o pagamento com mais de uma tentativa'
            );
        }
    }
}
