@php
    $paymentType = \App\Enums\PaymentType::MBWAY;
    $isActive = is_payment_active($paymentType, $payments);
    $paymentName = $paymentType->value;
@endphp

@if ($isActive)
    <x-payment-selector.option
        :name="$paymentName"
        :icon="asset('images/icons/' . $paymentName . '.png')"
    >
        {{ payment_visible_name($paymentType) }}
    </x-payment-selector.option>

    <div class="payment-fields" id="{{$paymentName}}-fields">
        <div class="mbway-form-group">
            <x-payment-selector.methods.mbway.phone-prefix />

            <input type="text" class="phone" name="phone" value="{{ old('phone') }}" placeholder="Número do telemóvel">
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mbwayPaymentOption = document.getElementById('payment-option-{{$paymentName}}');
            const mbwayFields = document.getElementById('{{$paymentName}}-fields');
            const phoneInput = document.getElementById('phone');
        
            document.querySelectorAll('input[name="payment_type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === '{{$paymentName}}') {
                        mbwayFields.classList.add('visible');
                        mbwayPaymentOption.classList.add('visible');
                        phoneInput.required = true;
                    } else {
                        mbwayFields.classList.remove('visible');
                        mbwayPaymentOption.classList.remove('visible');
                        phoneInput.required = false;
                    }
                });
            });
        });
    </script>
@endif
