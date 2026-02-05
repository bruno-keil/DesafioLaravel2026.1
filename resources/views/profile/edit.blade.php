<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LootBay - Perfil</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/welcome.css', 'resources/ts/welcome.ts'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1400&q=80'); opacity: 0.2;"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome ?? Auth::user()->name" />

            <div class="mt-8 mx-auto w-[min(1140px,92vw)]">
                <h1 class="font-['Bebas_Neue'] text-[clamp(2.5rem,5vw,3.5rem)] uppercase tracking-[0.12em] text-white">
                    Configurações de Perfil
                </h1>
            </div>
        </div>
    </header>

    <div class="py-12 pb-24">
        <div class="mx-auto w-[min(1140px,92vw)] space-y-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-8 rounded-2xl border border-red-500/20 bg-red-500/5 backdrop-blur-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
    
    <x-footer />
    <x-user-modal :auth-user-name="Auth::user()->nome ?? Auth::user()->name" />
</body>
</html>