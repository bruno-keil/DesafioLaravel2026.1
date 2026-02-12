<x-layouts.lootbay title="LootBay - Editar Usuário">
    <header class="relative overflow-hidden py-8">
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 mx-auto w-[min(1140px,92vw)]">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar para lista</a>
                <h1 class="font-['Bebas_Neue'] text-[clamp(2rem,5vw,3rem)] uppercase tracking-[0.12em] text-white">Editar Usuário</h1>
            </div>
        </div>
    </header>

    <section class="py-10 pb-20">
        <div class="mx-auto w-[min(1140px,92vw)]">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="rounded-2xl border border-white/10 bg-white/5 p-8 backdrop-blur-sm space-y-8">
                @csrf
                @method('PUT')

                <x-admin.personal-fields :model="$user" />
                <x-admin.password-fields :optional="true" />
                <x-admin.address-fields :address="$user->address" />

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2 rounded-full border border-white/20 text-white/70 hover:text-white hover:border-white/40 transition">Cancelar</a>
                    <button type="submit" class="px-6 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition shadow-lg shadow-emerald-500/20">Atualizar Usuário</button>
                </div>
            </form>
        </div>
    </section>

    <x-slot:scripts>
        <x-cep-autofill-script />
    </x-slot:scripts>
</x-layouts.lootbay>