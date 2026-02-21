<x-layouts.lootbay title="LootBay - Gerenciar Usuários">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 container-main">
                <a href="{{ route('dashboard') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar ao Dashboard</a>
                
                <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                    <div>
                        <h1 class="page-title">Gerenciar Usuários</h1>
                        <p class="text-white/60">Lista de todos os usuários comuns cadastrados.</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn-primary">
                        <i class="bi bi-plus-lg mr-2"></i>Novo Usuário
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section class="py-10">
        <div class="container-main">
            @if (session('success'))
                <div class="mb-6 alert-success">{{ session('success') }}</div>
            @endif

            <div class="glass-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead class="data-table-head">
                            <tr>
                                <th class="px-6 py-4">Nome</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">CPF</th>
                                <th class="px-6 py-4">Saldo</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach ($users as $user)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 font-medium text-white">{{ $user->nome }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->cpf }}</td>
                                    <td class="px-6 py-4 text-emerald-400">R$ {{ number_format($user->saldo, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-400 hover:text-white transition" title="Editar">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </a>
                                        <button
                                            type="button"
                                            class="text-red-400 hover:text-white transition"
                                            title="Excluir"
                                            x-data
                                            x-on:click="$dispatch('open-delete-modal', { action: '{{ route('admin.users.destroy', $user) }}', name: '{{ addslashes($user->nome) }}' })"
                                        >
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($users->isEmpty())
                    <div class="p-8 text-center text-white/40">Nenhum usuário encontrado.</div>
                @endif
                <div class="p-4 border-t border-white/10">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </section>
    <x-delete-modal />
</x-layouts.lootbay>