<?php

declare(strict_types=1);

namespace App\Actions;

use App\Tasks\Cb48\GetParsedCb48;
use App\Tasks\Instruction\CreateInstruction;
use App\Tasks\Instruction\GetInstruction;

class PaymentCb48
{
    public function __construct(
        private GetParsedCb48 $getParsedCb48,
        private CreateInstruction $createInstruction,
        private GetInstruction $getInstruction,
    ) {}

    public function execute(string $cb48): array
    {
        $instrucionDataRequest = $this->getParsedCb48->execute($cb48);

        $instructionId = $this->createInstruction->execute($instrucionDataRequest);

        $instruction = $this->getInstruction->execute($instructionId);

        return $instruction;
    }
}
