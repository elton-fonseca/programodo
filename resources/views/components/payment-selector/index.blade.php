<div class="payment-selector">
    <form class="payment-form" action="{{ route('payment.process') }}" method="POST">
        @csrf
        <input type="hidden" name="instruction_id" value="{{ $instruction['guid'] }}">

        <x-payment-selector.methods.card :payments="$payments" />
        <x-payment-selector.methods.mbway.component :payments="$payments" />
        <x-payment-selector.methods.payshop :payments="$payments" />
        <x-payment-selector.methods.multibanco :payments="$payments" />

        <x-payment-selector.methods.paypal :payments="$payments" />
        <x-payment-selector.methods.clicktopay :payments="$payments" />

        <x-payment-selector.methods.google-pay :payments="$payments" :instruction="$instruction" />
        <x-payment-selector.methods.apple-pay :payments="$payments" :instruction="$instruction" />


        <button type="submit" class="submit-btn">Confirmar Pagamento</button>
    </form>
</div>


