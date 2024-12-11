<?php

declare(strict_types=1);

namespace App\Tasks\Payment;

use App\Tasks\Instruction\CheckExpirationDate;
use App\Tasks\Instruction\CheckLinkAlreadyPayed;
use App\Tasks\Instruction\CheckPaymentAttempts;
use App\Tasks\Instruction\CheckVersionLimitations;

class InstructionValidation
{
    public function __construct(
        private CheckExpirationDate $checkExpirationDate,
        private CheckPaymentAttempts $checkPaymentAttempts,
        private CheckLinkAlreadyPayed $checkLinkAlreadyPayed,
        private CheckVersionLimitations $checkVersionLimitations,
    ) {}

    public function execute(array $instruction): void
    {
        $this->checkExpirationDate->execute($instruction);

        $this->checkVersionLimitations->execute($instruction);

        $this->checkPaymentAttempts->execute($instruction);
        $this->checkLinkAlreadyPayed->execute($instruction);
    }
}
