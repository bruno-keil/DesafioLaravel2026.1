<x-layouts.lootbay title="LootBay - Gerenciar Admins">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 container-main">
                 <a href="{{ route('dashboard') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar ao Dashboard</a>

                <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                    <div>
                        <h1 class="page-title">Gerenciar Admins</h1>
                        <p class="text-white/60">Lista de administradores do sistema.</p>
                    </div>
                    <a href="{{ route('admin.admins.create') }}" class="btn-primary">
                        <i class="bi bi-shield-plus mr-2"></i>Novo Admin
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
            @if (session('error'))
                <div class="mb-6 alert-error">{{ session('error') }}</div>
            @endif

            <div class="glass-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead class="data-table-head">
                            <tr>
                                <th class="px-6 py-4">Nome</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">CPF</th>
                                <th class="px-6 py-4">Criado Por</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach ($admins as $admin)
                                @php
                                    $canManage = ($admin->id === Auth::id()) || ($admin->created_by === Auth::id());
                                @endphp
                                <tr class="hover:bg-white/5 transition {{ !$canManage ? 'opacity-50' : '' }}">
                                    <td class="px-6 py-4 font-medium text-white flex items-center gap-2">
                                        {{ $admin->nome }}
                                        @if($admin->id === Auth::id())
                                            <span class="text-[0.6rem] uppercase border border-emerald-500 text-emerald-500 px-2 rounded-full">Você</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $admin->email }}</td>
                                    <td class="px-6 py-4">{{ $admin->cpf }}</td>
                                    <td class="px-6 py-4">
                                        @if($admin->created_by)
                                            ID: {{ $admin->created_by }}
                                        @else
                                            <span class="text-white/30">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right flex items-center justify-end gap-3">
                                        @if($canManage)
                                            <a href="{{ route('admin.admins.edit', $admin) }}" class="text-blue-400 hover:text-white transition" title="Editar">
                                                <i class="bi bi-pencil-square text-lg"></i>
                                            </a>
                                            <button
                                                type="button"
                                                class="text-red-400 hover:text-white transition"
                                                title="Excluir"
                                                x-data
                                                x-on:click="$dispatch('open-delete-modal', { action: '{{ route('admin.admins.destroy', $admin) }}', name: '{{ addslashes($admin->nome) }}' })"
                                            >
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        @else
                                            <span class="text-white/20 cursor-not-allowed" title="Você não tem permissão para gerenciar este admin"><i class="bi bi-lock-fill"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($admins->isEmpty())
                    <div class="p-8 text-center text-white/40">Nenhum administrador encontrado.</div>
                @endif
                <div class="p-4 border-t border-white/10">
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </section>
    <x-delete-modal />
</x-layouts.lootbay>