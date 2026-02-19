<x-layouts.lootbay title="LootBay - Editar Admin">
    <header class="relative overflow-hidden py-8">
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 container-main">
                <a href="{{ route('admin.admins.index') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar para lista</a>
                <h1 class="page-title">Editar Administrador</h1>
            </div>
        </div>
    </header>

    <section class="py-10 pb-20">
        <div class="container-main">
            <form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="glass-card p-8 backdrop-blur-sm space-y-8">
                @csrf
                @method('PUT')

                <x-admin.personal-fields :model="$admin" />
                <x-admin.password-fields :optional="true" />
                <x-admin.address-fields :address="$admin->address" />

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('admin.admins.index') }}" class="btn-ghost">Cancelar</a>
                    <button type="submit" class="btn-primary">Atualizar Admin</button>
                </div>
            </form>
        </div>
    </section>

    <x-slot:scripts>
        <x-cep-autofill-script />
    </x-slot:scripts>
</x-layouts.lootbay>