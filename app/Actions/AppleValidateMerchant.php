<?php

namespace App\Actions;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AppleValidateMerchant
{
    //https://developer.apple.com/documentation/apple_pay_on_the_web/apple_pay_js_api/requesting_an_apple_pay_payment_session
    public function execute(string $validationURL): array
    {
        $validationURL = $validationURL.'/paymentSession';
        //$validationURL = 'https://apple-pay-gateway.apple.com/paymentservices/paymentSession';

        $data = [
            'merchantIdentifier' => config('services.payshop.apple_pay.merchant_identifier'),
            'displayName' => config('services.payshop.apple_pay.merchant_name'),
            'initiative' => 'web',
            'initiativeContext' => config('services.payshop.apple_pay.domain_name'),
        ];

        $response = Http::withOptions([
            'cert' => storage_path('apple/'.config('services.payshop.apple_pay.certificate')),
            'ssl_key' => storage_path('apple/'.config('services.payshop.apple_pay.key')),
        ])->post($validationURL, $data);

        Log::info('Apple Pay Validation', ['response' => $response->body(), 'status' => $response->status()]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Merchant validation failed: '.$response->body());
    }
}
