<?php

declare(strict_types=1);

namespace App\Actions;

use App\Tasks\Instruction\CheckExpirationDate;
use App\Tasks\Instruction\CheckLinkAlreadyPayed;
use App\Tasks\Instruction\CheckPaymentAttempts;
use App\Tasks\Instruction\CheckVersionLimitations;
use App\Tasks\Instruction\GetInstruction;

class PaymentShow
{
    public function __construct(
        private GetInstruction $getInstruction,
        private CheckExpirationDate $checkExpirationDate,
        private CheckPaymentAttempts $checkPaymentAttempts,
        private CheckLinkAlreadyPayed $checkLinkAlreadyPayed,
        private CheckVersionLimitations $checkVersionLimitations,
    ) {}

    public function execute(string $instructionId)
    {
        $instruction = $this->getInstruction->execute($instructionId);

        $this->checkExpirationDate->execute($instruction);

        $this->checkVersionLimitations->execute($instruction);

        $this->checkLinkAlreadyPayed->execute($instruction);
        $this->checkPaymentAttempts->execute($instruction);

        return $instruction;
    }
}
