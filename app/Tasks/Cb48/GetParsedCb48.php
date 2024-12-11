<?php

namespace App\Tasks\Cb48;

class GetParsedCb48
{
    private const CB48_POSITIONS = [
        'BILL_ISSUER_START' => 3,
        'BILL_ISSUER_LENGTH' => 5,
        'CONSUMER_START' => 8,
        'CONSUMER_LENGTH' => 10,
        'AMOUNT_START' => 18,
        'AMOUNT_LENGTH' => 7,
        'DATE_START' => 32,
        'DATE_LENGTH' => 6,
    ];

    public function execute(string $cb48): array
    {
        $pos = self::CB48_POSITIONS;

        $billIssuerId = substr($cb48, $pos['BILL_ISSUER_START'], $pos['BILL_ISSUER_LENGTH']);
        $consumerId = substr($cb48, $pos['CONSUMER_START'], $pos['CONSUMER_LENGTH']);
        $rawAmount = substr($cb48, $pos['AMOUNT_START'], $pos['AMOUNT_LENGTH']);
        $dateString = substr($cb48, $pos['DATE_START'], $pos['DATE_LENGTH']);

        return [
            'billIssuerID' => $billIssuerId,
            'consumptionId' => $consumerId,
            'expiresAt' => $this->formatExpirationDate($dateString),
            'currency' => 'EUR',
            'amounts' => [
                [
                    'value' => $this->extractAmount($rawAmount),
                    'paymentMethods' => ['mbway', 'card', 'payshop'],
                ],
            ],
            'details' => [
                [
                    'name' => 'ContaCliente',
                    'type' => 'string',
                    'value' => $consumerId,
                    'showToUser' => true,
                    'labelToUser' => 'Nº conta Cliente',
                ],
            ],
        ];
    }

    private function formatExpirationDate(string $dateString): string
    {
        $year = substr($dateString, 0, 2);
        $month = substr($dateString, 2, 2);
        $day = substr($dateString, 4, 2);

        return sprintf('20%s-%s-%sT00:00:01Z', $year, $month, $day);
    }

    private function extractAmount(string $amountString): float
    {
        return intval('0'.$amountString) / 100;
    }
}
