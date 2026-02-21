@props([
    'title' => 'LootBay',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/ts/welcome.ts'])

    {{ $head ?? '' }}
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    {{ $slot }}

    <x-footer />

    @auth
        <x-user-modal :auth-user-name="Auth::user()->nome" />
    @endauth

    {{ $scripts ?? '' }}
</body>
</html>
