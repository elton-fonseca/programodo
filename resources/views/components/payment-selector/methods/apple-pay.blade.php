@php
    $paymentType = \App\Enums\PaymentType::APPLEPAY;
    $isActive = is_payment_active($paymentType, $payments);
@endphp

@if ($isActive)
  <script crossorigin="" src="https://applepay.cdn-apple.com/jsapi/1.latest/apple-pay-sdk.js"></script>

  <style>
    apple-pay-button {
      display: block;
      --apple-pay-button-height: 50px;
    }
  </style>

  <apple-pay-button id="applePayButton" buttonstyle="white-outline" type="pay" locale="pt-PT"></apple-pay-button>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Check if Apple Pay is available
      if (window.ApplePaySession && ApplePaySession.canMakePayments()) {
        const applePayButton = document.getElementById('applePayButton');

        // Add click event to the button
        applePayButton.addEventListener('click', async () => {
          const paymentRequest = {
            countryCode: 'PT',
            currencyCode: 'EUR',
            merchantCapabilities: ['supports3DS'],
            supportedNetworks: ['visa', 'masterCard', 'amex'],
            total: {
              label: '{{ config('services.payshop.google_pay.merchant_name') }}',
              //amount: '{{ $instruction['amounts'][0]['value'] }}',
              amount: '0.10',
            },
          };

          // Start the Apple Pay session
          const session = new ApplePaySession(3, paymentRequest);

          // Merchant validation
          session.onvalidatemerchant = async (event) => {
            try {
              const validationData = await fetch('{{ route('wallet.validate_marchant') }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ validationURL: event.validationURL }),
              }).then(res => res.json());

              session.completeMerchantValidation(validationData);
            } catch (error) {
              console.error('Merchant validation failed:', error);
              session.abort();
            }
          };

          // Payment authorized
          session.onpaymentauthorized = async (event) => {

            const instructionId = '{{ $instruction['guid'] }}';
            const paymentType = '{{ \App\Enums\PaymentType::APPLEPAY->value }}';
            const paymentData = event.payment.token;

            try {
              // Send payment data to the server
              const response = await fetch('{{ route('wallet.process') }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                  instruction_id: instructionId,
                  payment_type: paymentType,
                  payload: paymentData
                })
              });

              if (response.ok) {
                session.completePayment(ApplePaySession.STATUS_SUCCESS);
              } else {
                session.completePayment(ApplePaySession.STATUS_FAILURE);
              }
            } catch (error) {
              console.error('Payment processing failed:', error);
              session.completePayment(ApplePaySession.STATUS_FAILURE);
            }
          };

          // Start the session
          session.begin();
        });
      } else {
        console.log('Apple Pay is not available on this device/browser.');
      }
    });
  </script>
@endif