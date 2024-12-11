@php
    $paymentType = \App\Enums\PaymentType::PAYSHOP;
    $isActive = is_payment_active($paymentType, $payments);
@endphp

@if ($isActive)
    <x-payment-selector.option
        :name="$paymentType->value"
        :icon="asset('images/icons/' . $paymentType->value . '.png')"
    >
        {{ payment_visible_name($paymentType) }}
    </x-payment-selector.option>
@endif
