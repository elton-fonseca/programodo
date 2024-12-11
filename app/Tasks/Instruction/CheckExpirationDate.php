<?php

declare(strict_types=1);

namespace App\Tasks\Instruction;

use App\Exceptions\InstructionException;
use Carbon\Carbon;

class CheckExpirationDate
{
    public function execute(array $instruction): void
    {
        $expiresAt = new Carbon($instruction['expiresAt']);
        $now = new Carbon;

        if ($expiresAt < $now) {
            throw new InstructionException(
                title: 'Data limite de Pagamento foi ultrapassada',
                message: 'A data limite de pagamento deste QR Code foi ultrapassada'
            );
        }

    }
}
