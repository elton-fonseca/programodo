<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        @if (isset($instruction['clientCustomCssURL']))
            <link rel="stylesheet" href="{{ $instruction['clientCustomCssURL'] }}" crossorigin>
        @endif

        <title>{{ $title ?? 'Payshop' }}</title>
    </head>

    <body>
        <section class="main-content">
            <div class="container-content">
                @if (isset($instruction['clientCustomLogoURL']))
                    <img src="{{ $instruction['clientCustomLogoURL'] }}" alt="Logo" />
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $header }}

                @if ($instruction)
                    <x-payment-information
                        :value="$instruction['amounts'][0]['value']"
                        :expiresAt="$instruction['expiresAt']"
                        :details="$instruction['details']"
                    />
                @endif

                {{ $slot }}

                 <div class="bottom-content">
                    <p>Powered by</p>
                    <img style="margin: 0 auto;display: block;" src="{{ asset('images/logo-payshop.png') }}" alt="Icone payshop" width="150px">
                </div>
            </div>
        </section>
    </body>
</html>