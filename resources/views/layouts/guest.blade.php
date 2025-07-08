<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MoneyTrack') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                       <div>
                <a href="/">
                    {{-- Texto "MoneyTrack" acima do logo --}}
                    <h1 class="text-3xl font-semibold text-gray-800 mb-2"></h1>

                    {{-- Seu logotipo com metade do tamanho anterior --}}
                    <img src="{{ asset('images/moneytrack.png') }}" alt="MoneyTrack Logo" class="w-28 mx-auto">
                    {{-- 'w-28' define a largura para 7rem (112px), aproximadamente metade de w-56.
                         A altura será ajustada proporcionalmente. --}}
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
