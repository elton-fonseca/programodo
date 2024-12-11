<?php

namespace App\Tasks\Payment;

use App\Enums\PaymentType;
use App\Exceptions\PaymentException;
use App\Services\Payshop\PayshopClientFactoryInterface;

class GetPaymentServiceUUID
{
    public function __construct(
        private PayshopClientFactoryInterface $payshopFactory
    ) {}

    public function execute(array $instruction, PaymentType $paymentType): string
    {
        //remover para produção
        if (in_array($paymentType, [PaymentType::CARD, PaymentType::GOOGLEPAY])) {
            return 'D00E6D37-508F-4E9A-A21F-9B908B0DF93D';
        }

        $payshopClient = $this->payshopFactory->make(
            $instruction['gatewayStoreApiKey'],
            $instruction['gatewayStoreApiSignature'],
        );

        $response = $payshopClient->getClientServices(
            $instruction['gatewayStoreClientUUID']
        );

        if ($response['status'] !== 200) {
            throw new PaymentException(
                title: 'Erro ao obter serviço',
                message: 'Erro ao obter informações dos serviços de pagamento, tente novamente mais tarde',
            );
        }

        $serviceName = gateway_payment_name($paymentType);
        $services = collect($response['response']['services']);
        $service = $services->where('type', $serviceName)->first();

        if (! $service) {
            throw new PaymentException(
                message: 'Serviço de pagamento não encontrado no gateway',
                title: 'Erro ao obter serviço',
            );
        }

        return $service['uuid'];
    }
}
