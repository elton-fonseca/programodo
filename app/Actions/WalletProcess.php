<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\PaymentType;
use App\Tasks\Payment\CreatePaymentOrder;
use App\Tasks\Payment\InstructionValidation;
use App\Tasks\Wallet\SendWalletPayment;

class WalletProcess
{
    public function __construct(
        private InstructionValidation $instructionValidation,
        private CreatePaymentOrder $createPaymentOrder,
        private SendWalletPayment $sendWalletPayment,
    ) {}

    public function execute(array $instruction, array $paymentData): string
    {
        // $this->instructionValidation->execute($instruction);

        $paymentType = PaymentType::from($paymentData['payment_type']);

        $paymentOrder = $this->createPaymentOrder->execute($instruction, $paymentData, $paymentType);

        $walletPayment = $this->sendWalletPayment->execute(
            $instruction,
            $paymentOrder['uuid'],
            $paymentData['payload'],
            $paymentType
        );

        return $walletPayment['response']['details'];
    }
}
