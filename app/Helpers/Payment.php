
<?php

use App\Enums\PaymentType;
use Illuminate\Validation\Rule;

if (! function_exists('payment_visible_name')) {
    function payment_visible_name(PaymentType $paymentType): string
    {
        return config('services.payshop.payment_visible_names.'.$paymentType->value);
    }
}

if (! function_exists('gateway_payment_name')) {
    function gateway_payment_name(PaymentType $paymentType): string
    {
        return config('services.payshop.payment_services.'.$paymentType->value);
    }
}

if (! function_exists('pbl_payment_name')) {
    function pbl_payment_name(PaymentType $paymentType): string
    {
        return config('services.paybylink.payment_methods.'.$paymentType->value);
    }
}

if (! function_exists('is_payment_active')) {
    function is_payment_active(PaymentType $paymentType, array $payments): bool
    {
        return in_array(pbl_payment_name($paymentType), $payments);
    }
}

if (! function_exists('payment_type_rules')) {
    function payment_type_rules(): array
    {
        return ['required', Rule::in(array_column(PaymentType::cases(), 'value'))];
    }
}
