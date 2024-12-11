<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\PaymentType;
use App\Tasks\Payment\AddOrderIdMetadata;
use App\Tasks\Payment\CreatePaymentAttempt;
use App\Tasks\Payment\CreatePaymentOrder;
use App\Tasks\Payment\GetRedirectLink;
use App\Tasks\Payment\InstructionValidation;

class PaymentProcess
{
    public function __construct(
        private InstructionValidation $instructionValidation,
        private CreatePaymentOrder $createPaymentOrder,
        private AddOrderIdMetadata $addOrderIdMetadata,
        private CreatePaymentAttempt $createPaymentAttempt,
        private GetRedirectLink $getRedirectLink
    ) {}

    public function execute(array $instruction, array $paymentData): string
    {
        // $this->instructionValidation->execute($instruction);

        $paymentType = PaymentType::from($paymentData['payment_type']);

        $paymentOrder = $this->createPaymentOrder->execute($instruction, $paymentData, $paymentType);

        $this->createPaymentAttempt->execute($paymentOrder['uuid']);
        $this->addOrderIdMetadata->execute($paymentOrder['uuid'], $instruction);

        return $this->getRedirectLink->execute(
            $instruction,
            $paymentOrder['token'],
            $paymentType
        );
    }
}
