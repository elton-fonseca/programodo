<x-layout :instruction="$instruction">
    <x-slot name="header">
        <x-title>Confirmação de pagamento</x-title>

        <x-feedback>
            <h3 class="feedback-success">Pagamento finalizado com sucesso</h3>
            <img src="{{ asset('images/confirmation-green.png') }}" alt="Icone confirmação de pagamento" width="100px">
        </x-feedback>
    </x-slot>
</x-layout>