<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LootBay - Endereço de Entrega</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/welcome.css'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80'); opacity: 0.3;"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 to-[#0a0f16]"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="$isAuthenticated" :auth-user-name="$authUserName" />
            <div class="mt-8 mx-auto w-[min(1140px,92vw)]">
                <h1 class="font-['Bebas_Neue'] text-[clamp(2.5rem,5vw,3.5rem)] uppercase tracking-[0.12em]">Checkout</h1>
                <div class="flex items-center gap-2 text-sm text-white/50 mt-2">
                    <span>Carrinho</span>
                    <i class="bi bi-chevron-right text-xs"></i>
                    <span class="text-emerald-400">Endereço</span>
                    <i class="bi bi-chevron-right text-xs"></i>
                    <span>Pagamento</span>
                </div>
            </div>
        </div>
    </header>

    <section class="py-10 pb-20">
        <div class="mx-auto w-[min(1140px,92vw)] grid gap-8 lg:grid-cols-3">
            
            <div class="lg:col-span-2 space-y-6">
                @if (session('success'))
                    <div class="rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{{ session('success') }}</div>
                @endif

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 md:p-8">
                    <h2 class="text-2xl font-semibold text-white mb-6">Endereço de Entrega</h2>
                    
                    @if($address && !session('edit_mode'))
                        <div class="bg-white/5 border border-white/10 rounded-xl p-6 mb-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-bold text-lg text-emerald-400">Endereço Principal</h3>
                                    <p class="text-white/80 mt-2">{{ $address->logradouro }}, {{ $address->numero }}</p>
                                    <p class="text-white/60">{{ $address->bairro }} - {{ $address->cidade }}/{{ $address->uf }}</p>
                                    <p class="text-white/60">{{ $address->cep }}</p>
                                    @if($address->complemento)
                                        <p class="text-white/50 text-sm mt-1">{{ $address->complemento }}</p>
                                    @endif
                                </div>
                                <button onclick="toggleAddressForm()" class="text-sm text-emerald-400 hover:text-emerald-300 underline">
                                    Alterar
                                </button>
                            </div>
                        </div>

                        <form method="post" action="{{ route('checkout.create') }}">
                            @csrf
                            <input type="hidden" name="items" value="{{ json_encode($items) }}">
                            <button class="w-full rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 text-center transition shadow-lg shadow-emerald-500/20 uppercase tracking-widest text-sm">
                                Ir para Pagamento
                            </button>
                        </form>
                    @endif

                    <div id="addressForm" class="{{ ($address && !session('edit_mode')) ? 'hidden' : '' }}">
                        <form action="{{ route('checkout.address.update') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">CEP</label>
                                    <input type="text" name="cep" id="cep" maxlength="8" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 placeholder-white/20" placeholder="00000000" value="{{ old('cep', $address->cep ?? '') }}" required>
                                </div>
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Estado (UF)</label>
                                    <input type="text" name="uf" id="uf" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"  value="{{ old('uf', $address->uf ?? '') }}" readonly required>
                                    <input type="hidden" name="estado" id="estado" value="{{ old('estado', $address->estado ?? '') }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-3">
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Logradouro</label>
                                    <input type="text" name="logradouro" id="logradouro" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('logradouro', $address->logradouro ?? '') }}" required>
                                </div>
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Número</label>
                                    <input type="text" name="numero" id="numero" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('numero', $address->numero ?? '') }}" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('bairro', $address->bairro ?? '') }}" required>
                                </div>
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('cidade', $address->cidade ?? '') }}" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Complemento</label>
                                <input type="text" name="complemento" id="complemento" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('complemento', $address->complemento ?? '') }}">
                            </div>

                            <div class="pt-4 flex items-center justify-end gap-3">
                                @if($address)
                                    <button type="button" onclick="toggleAddressForm()" class="px-6 py-2 rounded-full border border-white/20 text-white/70 hover:text-white hover:border-white/40 transition">Cancelar</button>
                                @endif
                                <button type="submit" class="px-6 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-500/20 transition">Salvar Endereço</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 sticky top-6">
                    <h3 class="font-['Bebas_Neue'] text-2xl tracking-wide mb-4">Resumo do Pedido</h3>
                    
                    <div class="space-y-3 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($items as $item)
                            <div class="flex items-center gap-3 text-sm">
                                <div class="h-10 w-10 rounded bg-cover bg-center shrink-0" style="background-image: url('{{ $item['photo'] }}')"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="truncate text-white">{{ $item['name'] }}</p>
                                    <p class="text-white/50 text-xs">{{ $item['quantity'] }}x R$ {{ number_format($item['price'], 2, ',', '.') }}</p>
                                </div>
                                <div class="text-emerald-300 font-medium">
                                    R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-white/10 pt-4 mt-4">
                        <div class="flex items-center justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span class="text-emerald-400">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('cart.index') }}" class="block text-center text-sm text-white/40 hover:text-white mt-6 underline decoration-white/20 hover:decoration-white transition">
                        Voltar para o carrinho
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-footer />
    @if ($isAuthenticated)
        <x-user-modal :auth-user-name="$authUserName" />
    @endif

    <script>
        function toggleAddressForm() {
            const form = document.getElementById('addressForm');
            form.classList.toggle('hidden');
        }
        document.getElementById('cep').addEventListener('input', function(e) {
            let cep = e.target.value.replace(/\D/g, '');
            
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('logradouro').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('uf').value = data.uf;
                            document.getElementById('estado').value = data.uf;
                            
                            document.getElementById('numero').focus();
                        }
                    })
                    .catch(error => console.error('Erro ao buscar CEP:', error));
            }
        });
    </script>
</body>
</html>