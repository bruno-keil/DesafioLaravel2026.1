<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LootBay - Gerenciar Admins</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/welcome.css'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 mx-auto w-[min(1140px,92vw)]">
                 <a href="{{ route('dashboard') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar ao Dashboard</a>

                <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                    <div>
                        <h1 class="font-['Bebas_Neue'] text-[clamp(2rem,5vw,3rem)] uppercase tracking-[0.12em] text-white">Gerenciar Admins</h1>
                        <p class="text-white/60">Lista de administradores do sistema.</p>
                    </div>
                    <a href="{{ route('admin.admins.create') }}" class="px-6 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition shadow-lg shadow-emerald-500/20">
                        <i class="bi bi-shield-plus mr-2"></i>Novo Admin
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section class="py-10">
        <div class="mx-auto w-[min(1140px,92vw)]">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">{{ session('error') }}</div>
            @endif

            <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-white/70">
                        <thead class="bg-white/10 text-xs uppercase tracking-wider text-white">
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
                                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este administrador?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-white transition" title="Excluir">
                                                    <i class="bi bi-trash text-lg"></i>
                                                </button>
                                            </form>
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
    <x-footer />
    <x-user-modal :auth-user-name="Auth::user()->nome" />
</body>
</html>