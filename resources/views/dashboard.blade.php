<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LootBay - Dashboard</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/welcome.css'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    <header class="relative overflow-hidden py-12">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=1400&q=80'); opacity: 0.2;"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome ?? Auth::user()->name" />

            <div class="mt-12 mx-auto w-[min(1140px,92vw)]">
                <h1 class="font-['Bebas_Neue'] text-[clamp(3rem,6vw,4.2rem)] uppercase tracking-[0.12em] text-white">
                    Dashboard
                </h1>
                <div class="flex flex-wrap items-center gap-4 mt-2">
                    <p class="text-white/70 text-lg">Bem-vindo de volta, <span class="text-emerald-400 font-semibold">{{ Auth::user()->nome ?? Auth::user()->name }}</span>.</p>
                    @if(Auth::user()->is_admin)
                        <span class="px-3 py-1 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold uppercase tracking-wider">Administrador</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider">Usuário</span>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <section class="py-14">
        <div class="mx-auto w-[min(1140px,92vw)] space-y-12">
            
            <div class="grid grid-cols-1 {{ !Auth::user()->is_admin ? 'lg:grid-cols-2' : '' }} gap-6">
                @if(Auth::user()->is_admin)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                            <i class="bi bi-box-seam text-6xl text-blue-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Cadastros de Produtos</h3>
                        <p class="text-white/50 text-sm mb-6">Novos produtos cadastrados nos últimos 12 meses.</p>
                        <div class="h-48 w-full rounded-xl bg-gradient-to-b from-blue-500/10 to-transparent border border-blue-500/20 flex items-end justify-between px-4 pb-2 gap-2">
                            @for($i = 0; $i < 12; $i++)
                                <div class="w-full bg-blue-500/40 hover:bg-blue-400 transition-all rounded-t-sm" style="height: {{ rand(20, 90) }}%"></div>
                            @endfor
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                            <i class="bi bi-graph-up-arrow text-6xl text-emerald-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Desempenho de Vendas</h3>
                        <p class="text-white/50 text-sm mb-6">Vendas realizadas nos últimos 12 meses.</p>
                        <div class="h-48 w-full rounded-xl bg-gradient-to-b from-emerald-500/10 to-transparent border border-emerald-500/20 flex items-end justify-between px-4 pb-0 relative">
                            <svg class="absolute inset-0 h-full w-full p-4" viewBox="0 0 100 50" preserveAspectRatio="none">
                                <path d="M0 40 Q 10 35, 20 20 T 40 25 T 60 10 T 80 15 T 100 5" fill="none" stroke="#34d399" stroke-width="2" />
                            </svg>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm flex flex-col justify-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <i class="bi bi-wallet2 text-6xl text-white"></i>
                        </div>
                        <p class="text-white/50 text-sm uppercase tracking-wider mb-1">Saldo em Conta</p>
                        <div class="text-4xl font-['Bebas_Neue'] text-white tracking-wide">
                            R$ {{ number_format(Auth::user()->saldo ?? 0, 2, ',', '.') }}
                        </div>
                        <div class="mt-4 flex gap-3">
                            <button class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-sm transition">Adicionar Fundos</button>
                            <button class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-sm transition">Extrato</button>
                        </div>
                    </div>
                @endif
            </div>

            @if(!Auth::user()->is_admin)
                <div>
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-purple-500 rounded-full inline-block"></span>
                        Compras
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('products.index') }}" class="group p-6 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/[0.08] hover:border-purple-500/40 transition duration-300">
                            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:text-white transition">
                                <i class="bi bi-shop text-xl"></i>
                            </div>
                            <h3 class="font-bold text-white mb-1">Explorar Loja</h3>
                            <p class="text-xs text-white/50">Navegue e compre produtos.</p>
                        </a>

                        <a href="{{ route('cart.index') }}" class="group p-6 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/[0.08] hover:border-purple-500/40 transition duration-300">
                            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:text-white transition">
                                <i class="bi bi-cart3 text-xl"></i>
                            </div>
                            <h3 class="font-bold text-white mb-1">Meu Carrinho</h3>
                            <p class="text-xs text-white/50">Visualize itens pendentes.</p>
                        </a>

                        <a href="#" class="group p-6 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/[0.08] hover:border-purple-500/40 transition duration-300">
                            <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:text-white transition">
                                <i class="bi bi-bag-check text-xl"></i>
                            </div>
                            <h3 class="font-bold text-white mb-1">Minhas Compras</h3>
                            <p class="text-xs text-white/50">Histórico e relatórios em PDF.</p>
                        </a>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-emerald-500 rounded-full inline-block"></span>
                        Vendas
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('products.my') }}" class="group p-6 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/[0.08] hover:border-emerald-500/40 transition duration-300">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-4 group-hover:bg-emerald-500 group-hover:text-white transition">
                                <i class="bi bi-tags text-xl"></i>
                            </div>
                            <h3 class="font-bold text-white mb-1">Meus Produtos</h3>
                            <p class="text-xs text-white/50">Cadastre e gerencie anúncios.</p>
                        </a>

                        <a href="#" class="group p-6 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/[0.08] hover:border-emerald-500/40 transition duration-300">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-4 group-hover:bg-emerald-500 group-hover:text-white transition">
                                <i class="bi bi-currency-dollar text-xl"></i>
                            </div>
                            <h3 class="font-bold text-white mb-1">Minhas Vendas</h3>
                            <p class="text-xs text-white/50">Histórico de vendas e relatórios.</p>
                        </a>
                    </div>
                </div>
            @endif

            @if(Auth::user()->is_admin)
            <div>
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <span class="w-1 h-6 bg-red-500 rounded-full inline-block"></span>
                    Administração
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.users.index') }}" class="group p-6 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/[0.08] hover:border-red-500/40 transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-400 flex items-center justify-center mb-4 group-hover:bg-red-500 group-hover:text-white transition">
                            <i class="bi bi-people text-xl"></i>
                        </div>
                        <h3 class="font-bold text-white mb-1">Usuários</h3>
                        <p class="text-xs text-white/50">Gerenciar contas de usuários.</p>
                    </a>

                    <a href="{{ route('admin.admins.index') }}" class="group p-6 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/[0.08] hover:border-red-500/40 transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-400 flex items-center justify-center mb-4 group-hover:bg-red-500 group-hover:text-white transition">
                            <i class="bi bi-shield-lock text-xl"></i>
                        </div>
                        <h3 class="font-bold text-white mb-1">Administradores</h3>
                        <p class="text-xs text-white/50">Gerenciar acessos administrativos.</p>
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="group p-6 rounded-2xl border border-white/10 bg-white/5 hover:bg-white/[0.08] hover:border-red-500/40 transition duration-300">
                        <div class="w-12 h-12 rounded-xl bg-red-500/10 text-red-400 flex items-center justify-center mb-4 group-hover:bg-red-500 group-hover:text-white transition">
                            <i class="bi bi-box-seam text-xl"></i>
                        </div>
                        <h3 class="font-bold text-white mb-1">Todos Produtos</h3>
                        <p class="text-xs text-white/50">Moderação de produtos.</p>
                    </a>
                </div>
            </div>
            @endif

            <div>
                <div class="border-t border-white/10 pt-8">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-3 text-white/60 hover:text-white transition group">
                        <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:border-white/30 transition">
                            <i class="bi bi-gear"></i>
                        </div>
                        <span class="font-medium">Configurações de Perfil</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <x-footer />
    <x-user-modal :auth-user-name="Auth::user()->nome ?? Auth::user()->name" />
</body>
</html>