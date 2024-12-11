<x-layout :instruction="$instruction">
    <x-slot name="header">
        <x-title>{{ session('error.title') ?? 'Pagamento não efetuado' }}</x-title>

        <div style="margin-top: 50px;"></div>

        <x-feedback>
            <img src="{{ asset('images/alert.png') }}" alt="Icone confirmação de pagamento" width="100px">
            <h3 style="color: #404040; text-align: center">{!! session('error.message') ?? 'Erro ao realizar o pagamento' !!}</h3>
        </x-feedback>
                    
        <div style="margin-top: 50px;"></div>
    </x-slot>
</x-layout>