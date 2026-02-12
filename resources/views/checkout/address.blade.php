<x-layouts.lootbay title="LootBay - Endereço de Entrega">
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
                @if (session('error'))
                    <div class="rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">{{ session('error') }}</div>
                @endif
                @if (session('address-success'))
                    <div class="rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{{ session('address-success') }}</div>
                @endif
                @if (session('address-error'))
                    <div class="rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">{{ session('address-error') }}</div>
                @endif

                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 md:p-8">
                    <h2 class="text-2xl font-semibold text-white mb-6">Endereço de Entrega</h2>

                    @if($addresses->count() > 0)
                        <div class="space-y-3 mb-6">
                            @foreach($addresses as $addr)
                                <div class="flex items-start gap-4 rounded-xl border {{ $addr->is_default ? 'border-emerald-500/40 bg-emerald-500/5' : 'border-white/10 bg-white/5' }} p-4 transition hover:border-emerald-500/30 cursor-pointer"
                                     onclick="selectAddress({{ $addr->id }})">
                                    <input type="radio" name="selected_address" value="{{ $addr->id }}"
                                           {{ $addr->is_default ? 'checked' : '' }}
                                           class="mt-1 accent-emerald-500 pointer-events-none">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-white">{{ $addr->nome }}</span>
                                            @if($addr->is_default)
                                                <span class="rounded-full bg-emerald-500/20 px-2 py-0.5 text-xs text-emerald-400 border border-emerald-500/30">Selecionado</span>
                                            @endif
                                        </div>
                                        <p class="text-white/80 mt-1 text-sm">{{ $addr->logradouro }}, {{ $addr->numero }}</p>
                                        <p class="text-white/60 text-sm">{{ $addr->bairro }} — {{ $addr->cidade }}/{{ $addr->uf }}</p>
                                        <p class="text-white/50 text-xs">CEP: {{ substr($addr->cep, 0, 5) }}-{{ substr($addr->cep, 5) }}</p>
                                        @if($addr->complemento)
                                            <p class="text-white/40 text-xs">{{ $addr->complemento }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($addresses->count() < 10)
                            <div id="checkout-new-address-trigger"
                                 onclick="toggleNewAddressForm()"
                                 class="rounded-xl border-2 border-dashed border-white/10 p-4 hover:border-emerald-500/30 transition cursor-pointer group mb-6">
                                <div class="flex items-center justify-center gap-3 text-white/40 group-hover:text-emerald-400 transition">
                                    <i class="bi bi-plus-circle text-xl"></i>
                                    <span class="font-medium">Adicionar novo endereço</span>
                                </div>
                            </div>

                            <div id="checkout-new-address-form" class="hidden rounded-xl border border-emerald-500/30 bg-emerald-500/5 p-5 mb-6">
                                <h3 class="font-bold text-white mb-4">Novo Endereço</h3>
                                <form method="POST" action="{{ route('addresses.store') }}" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="_redirect" value="{{ route('checkout.address') }}">

                                    <div>
                                        <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Nome do endereço</label>
                                        <input type="text" name="nome" placeholder="Ex: Casa, Trabalho..." class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 placeholder-white/20" required maxlength="50">
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">CEP</label>
                                            <input type="text" name="cep" id="checkout-new-cep" maxlength="8" placeholder="00000000" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 placeholder-white/20" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">UF</label>
                                            <input type="text" name="uf" id="checkout-new-uf" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" readonly required>
                                            <input type="hidden" name="estado" id="checkout-new-estado">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-4 gap-3">
                                        <div class="col-span-3">
                                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Logradouro</label>
                                            <input type="text" name="logradouro" id="checkout-new-logradouro" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Número</label>
                                            <input type="text" name="numero" id="checkout-new-numero" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Bairro</label>
                                            <input type="text" name="bairro" id="checkout-new-bairro" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Cidade</label>
                                            <input type="text" name="cidade" id="checkout-new-cidade" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Complemento</label>
                                        <input type="text" name="complemento" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                                    </div>

                                    <div class="flex items-center justify-end gap-2 pt-2">
                                        <button type="button" onclick="toggleNewAddressForm()" class="px-4 py-2 rounded-full border border-white/20 text-white/60 text-sm hover:text-white hover:border-white/40 transition">Cancelar</button>
                                        <button type="submit" class="px-4 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold shadow-lg shadow-emerald-500/20 transition">Adicionar</button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 px-4 py-3 text-sm text-amber-200 mb-6">
                                Limite de 10 endereços. Remova um no <a href="{{ route('profile.edit') }}" class="underline text-amber-300 hover:text-amber-100">perfil</a> para adicionar outro.
                            </div>
                        @endif

                        <form method="post" action="{{ route('checkout.create') }}">
                            @csrf
                            <input type="hidden" name="items" value="{{ json_encode($items) }}">
                            <button class="w-full rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 text-center transition shadow-lg shadow-emerald-500/20 uppercase tracking-widest text-sm">
                                Ir para Pagamento
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('addresses.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="_redirect" value="{{ route('checkout.address') }}">

                            <div>
                                <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Nome do endereço</label>
                                <input type="text" name="nome" placeholder="Ex: Casa, Trabalho..." class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 placeholder-white/20" value="{{ old('nome') }}" required maxlength="50">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">CEP</label>
                                    <input type="text" name="cep" id="cep" maxlength="8" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 placeholder-white/20" placeholder="00000000" value="{{ old('cep') }}" required>
                                </div>
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Estado (UF)</label>
                                    <input type="text" name="uf" id="uf" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('uf') }}" readonly required>
                                    <input type="hidden" name="estado" id="estado" value="{{ old('estado') }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-3">
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Logradouro</label>
                                    <input type="text" name="logradouro" id="logradouro" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('logradouro') }}" required>
                                </div>
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Número</label>
                                    <input type="text" name="numero" id="numero" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('numero') }}" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Bairro</label>
                                    <input type="text" name="bairro" id="bairro" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('bairro') }}" required>
                                </div>
                                <div>
                                    <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Cidade</label>
                                    <input type="text" name="cidade" id="cidade" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('cidade') }}" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Complemento</label>
                                <input type="text" name="complemento" id="complemento" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" value="{{ old('complemento') }}">
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full px-6 py-3 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-500/20 transition">Salvar Endereço</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6 sticky top-6">
                    <h3 class="font-['Bebas_Neue'] text-2xl tracking-wide mb-4">Resumo do Pedido</h3>
                    
                    <div class="space-y-3 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($items as $item)
                            <div class="flex items-center gap-3 text-sm">
                                <img class="h-10 w-10 rounded object-cover shrink-0" src="{{ $item['photo'] }}" alt="{{ $item['name'] }}">
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

    <x-slot:scripts>
        <script>
            function toggleNewAddressForm() {
                document.getElementById('checkout-new-address-trigger').classList.toggle('hidden');
                document.getElementById('checkout-new-address-form').classList.toggle('hidden');
            }

            function selectAddress(addressId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("checkout.address.update") }}';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const id = document.createElement('input');
                id.type = 'hidden';
                id.name = 'address_id';
                id.value = addressId;
                form.appendChild(id);

                document.body.appendChild(form);
                form.submit();
            }

            function setupCepAutofill(cepInput) {
                cepInput.addEventListener('input', function(e) {
                    let cep = e.target.value.replace(/\D/g, '');
                    if (cep.length === 8) {
                        const form = cepInput.closest('form');
                        fetch(`https://viacep.com.br/ws/${cep}/json/`)
                            .then(r => r.json())
                            .then(data => {
                                if (!data.erro) {
                                    const get = (name) => form.querySelector(`[name="${name}"]`);
                                    if (get('logradouro')) get('logradouro').value = data.logradouro;
                                    if (get('bairro')) get('bairro').value = data.bairro;
                                    if (get('cidade')) get('cidade').value = data.localidade;
                                    if (get('uf')) get('uf').value = data.uf;
                                    if (get('estado')) get('estado').value = data.uf;
                                    const numero = get('numero');
                                    if (numero) numero.focus();
                                }
                            })
                            .catch(err => console.error('Erro ao buscar CEP:', err));
                    }
                });
            }

            document.querySelectorAll('input[name="cep"]').forEach(setupCepAutofill);
        </script>
    </x-slot:scripts>
</x-layouts.lootbay>