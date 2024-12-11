<x-layout :instruction="$instruction">
    <x-slot name="header">
        <x-title>Pagamento</x-title>

        <x-feedback>
            <p>Valide na fatura os <strong>dados de pagamento:</strong></p>
        </x-feedback>
    </x-slot>

    <x-payment-selector :payments="$instruction['amounts'][0]['paymentMethods']" :instruction="$instruction"/>
</x-layout>