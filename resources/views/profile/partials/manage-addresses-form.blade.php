<section>
    <header>
        <h2 class="text-xl font-semibold text-white">
            Meus Endereços
        </h2>
        <p class="mt-1 text-sm text-white/60">
            Gerencie seus endereços de entrega. Você pode salvar até 10 endereços.
        </p>
    </header>

    @if (session('address-success'))
        <div class="mt-4 alert-success">
            {{ session('address-success') }}
        </div>
    @endif

    @if (session('address-error'))
        <div class="mt-4 alert-error">
            {{ session('address-error') }}
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 gap-4">
        @foreach ($addresses as $address)
            <div class="group relative rounded-xl border {{ $address->is_default ? 'border-emerald-500/40 bg-emerald-500/5' : 'border-white/10 bg-white/5' }} p-5 transition hover:border-white/20"
                 id="address-card-{{ $address->id }}">

                <div id="address-view-{{ $address->id }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-white">{{ $address->nome }}</h3>
                                @if ($address->is_default)
                                    <span class="rounded-full bg-emerald-500/20 px-2 py-0.5 text-xs text-emerald-400 border border-emerald-500/30">Padrão</span>
                                @endif
                            </div>
                            <p class="text-white/80 mt-1">{{ $address->logradouro }}, {{ $address->numero }}</p>
                            <p class="text-white/60 text-sm">{{ $address->bairro }} — {{ $address->cidade }}/{{ $address->uf }}</p>
                            <p class="text-white/50 text-sm">CEP: {{ substr($address->cep, 0, 5) }}-{{ substr($address->cep, 5) }}</p>
                            @if ($address->complemento)
                                <p class="text-white/40 text-sm mt-0.5">{{ $address->complemento }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @unless ($address->is_default)
                                <form method="POST" action="{{ route('addresses.default', $address) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs text-white/40 hover:text-emerald-400 transition" title="Definir como padrão">
                                        <i class="bi bi-star text-base"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-emerald-400" title="Endereço padrão">
                                    <i class="bi bi-star-fill text-base"></i>
                                </span>
                            @endunless

                            <button type="button" onclick="toggleEditAddress({{ $address->id }})" class="text-xs text-white/40 hover:text-emerald-400 transition" title="Editar">
                                <i class="bi bi-pencil text-base"></i>
                            </button>

                            <form method="POST" action="{{ route('addresses.destroy', $address) }}" onsubmit="return confirm('Tem certeza que deseja remover este endereço?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-white/40 hover:text-red-400 transition" title="Remover">
                                    <i class="bi bi-trash text-base"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="address-edit-{{ $address->id }}" class="hidden">
                    <form method="POST" action="{{ route('addresses.update', $address) }}" class="space-y-3">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="form-label-dark">Nome do endereço</label>
                            <input type="text" name="nome" value="{{ $address->nome }}" class="form-input-dark-sm" required maxlength="50">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label-dark">CEP</label>
                                <input type="text" name="cep" value="{{ $address->cep }}" maxlength="8" class="address-cep form-input-dark-sm" required>
                            </div>
                            <div>
                                <label class="form-label-dark">UF</label>
                                <input type="text" name="uf" value="{{ $address->uf }}" class="address-uf form-input-dark-sm" readonly required>
                                <input type="hidden" name="estado" value="{{ $address->estado }}" class="address-estado">
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-3">
                            <div class="col-span-3">
                                <label class="form-label-dark">Logradouro</label>
                                <input type="text" name="logradouro" value="{{ $address->logradouro }}" class="address-logradouro form-input-dark-sm" required>
                            </div>
                            <div>
                                <label class="form-label-dark">Número</label>
                                <input type="text" name="numero" value="{{ $address->numero }}" class="address-numero form-input-dark-sm" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label-dark">Bairro</label>
                                <input type="text" name="bairro" value="{{ $address->bairro }}" class="address-bairro form-input-dark-sm" required>
                            </div>
                            <div>
                                <label class="form-label-dark">Cidade</label>
                                <input type="text" name="cidade" value="{{ $address->cidade }}" class="address-cidade form-input-dark-sm" required>
                            </div>
                        </div>

                        <div>
                            <label class="form-label-dark">Complemento</label>
                            <input type="text" name="complemento" value="{{ $address->complemento }}" class="form-input-dark-sm">
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" onclick="toggleEditAddress({{ $address->id }})" class="px-4 py-2 rounded-full border border-white/20 text-white/60 text-sm hover:text-white hover:border-white/40 transition">Cancelar</button>
                            <button type="submit" class="px-4 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold shadow-lg shadow-emerald-500/20 transition">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        @if ($addresses->count() < 10)
            <div id="new-address-trigger" class="rounded-xl border-2 border-dashed border-white/10 p-5 hover:border-emerald-500/30 transition cursor-pointer group"
                 onclick="toggleNewAddressForm()">
                <div class="flex items-center justify-center gap-3 text-white/40 group-hover:text-emerald-400 transition">
                    <i class="bi bi-plus-circle text-2xl"></i>
                    <span class="font-medium">Adicionar novo endereço</span>
                </div>
            </div>

            <div id="new-address-form" class="hidden rounded-xl border border-emerald-500/30 bg-emerald-500/5 p-5">
                <h3 class="font-bold text-white mb-4">Novo Endereço</h3>
                <form method="POST" action="{{ route('addresses.store') }}" class="space-y-3">
                    @csrf

                    <div>
                        <label class="form-label-dark">Nome do endereço</label>
                        <input type="text" name="nome" placeholder="Ex: Casa, Trabalho, Mãe..." class="form-input-dark-sm placeholder-white/20" required maxlength="50">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label-dark">CEP</label>
                            <input type="text" name="cep" id="new-address-cep" maxlength="8" placeholder="00000000" class="form-input-dark-sm placeholder-white/20" required>
                        </div>
                        <div>
                            <label class="form-label-dark">UF</label>
                            <input type="text" name="uf" id="new-address-uf" class="form-input-dark-sm" readonly required>
                            <input type="hidden" name="estado" id="new-address-estado">
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-3">
                        <div class="col-span-3">
                            <label class="form-label-dark">Logradouro</label>
                            <input type="text" name="logradouro" id="new-address-logradouro" class="form-input-dark-sm" required>
                        </div>
                        <div>
                            <label class="form-label-dark">Número</label>
                            <input type="text" name="numero" id="new-address-numero" class="form-input-dark-sm" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label-dark">Bairro</label>
                            <input type="text" name="bairro" id="new-address-bairro" class="form-input-dark-sm" required>
                        </div>
                        <div>
                            <label class="form-label-dark">Cidade</label>
                            <input type="text" name="cidade" id="new-address-cidade" class="form-input-dark-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="form-label-dark">Complemento</label>
                        <input type="text" name="complemento" class="form-input-dark-sm">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" onclick="toggleNewAddressForm()" class="px-4 py-2 rounded-full border border-white/20 text-white/60 text-sm hover:text-white hover:border-white/40 transition">Cancelar</button>
                        <button type="submit" class="px-4 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold shadow-lg shadow-emerald-500/20 transition">Adicionar</button>
                    </div>
                </form>
            </div>
        @else
            <div class="rounded-xl border border-amber-500/20 bg-amber-500/5 px-4 py-3 text-sm text-amber-200">
                Você atingiu o limite máximo de 10 endereços. Remova um endereço para adicionar outro.
            </div>
        @endif
    </div>

    <script>
        function toggleEditAddress(id) {
            document.getElementById('address-view-' + id).classList.toggle('hidden');
            document.getElementById('address-edit-' + id).classList.toggle('hidden');
        }

        function toggleNewAddressForm() {
            document.getElementById('new-address-trigger').classList.toggle('hidden');
            document.getElementById('new-address-form').classList.toggle('hidden');
        }

        function setupCepAutofill(cepInput, prefix) {
            cepInput.addEventListener('input', function (e) {
                let cep = e.target.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(r => r.json())
                        .then(data => {
                            if (!data.erro) {
                                const container = cepInput.closest('form');
                                const get = (name) => container.querySelector(`[name="${name}"]`) || container.querySelector(`.address-${name}`);
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

        const newCep = document.getElementById('new-address-cep');
        if (newCep) setupCepAutofill(newCep, 'new-address');

        document.querySelectorAll('.address-cep').forEach(input => {
            setupCepAutofill(input, '');
        });
    </script>
</section>
