<x-layouts.lootbay title="LootBay - Perfil">
    <x-slot:head>
        @vite(['resources/js/app.js'])
    </x-slot:head>

    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1400&q=80'); opacity: 0.2;"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />

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

            <div class="p-8 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm">
                @include('profile.partials.manage-addresses-form')
            </div>

            <div class="p-8 rounded-2xl border border-red-500/20 bg-red-500/5 backdrop-blur-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-layouts.lootbay>