<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LootBay - Editar Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/welcome.css'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    <header class="relative overflow-hidden py-8">
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 mx-auto w-[min(1140px,92vw)]">
                <a href="{{ route('admin.admins.index') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar para lista</a>
                <h1 class="font-['Bebas_Neue'] text-[clamp(2rem,5vw,3rem)] uppercase tracking-[0.12em] text-white">Editar Administrador</h1>
            </div>
        </div>
    </header>

    <section class="py-10 pb-20">
        <div class="mx-auto w-[min(1140px,92vw)]">
            <form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="rounded-2xl border border-white/10 bg-white/5 p-8 backdrop-blur-sm space-y-8">
                @csrf
                @method('PUT')
                
                <div>
                    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">Dados Pessoais</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Nome Completo</label>
                            <input type="text" name="nome" value="{{ old('nome', $admin->nome) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            <x-input-error :messages="$errors->get('nome')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">CPF</label>
                            <input type="text" name="cpf" value="{{ old('cpf', $admin->cpf) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            <x-input-error :messages="$errors->get('cpf')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" value="{{ old('data_nascimento', $admin->data_nascimento?->format('Y-m-d')) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            <x-input-error :messages="$errors->get('data_nascimento')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Telefone</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $admin->telefone) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            <x-input-error :messages="$errors->get('telefone')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">Segurança (Opcional)</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Nova Senha</label>
                            <input type="password" name="password" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" placeholder="Deixe em branco para manter">
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Confirmar Nova Senha</label>
                            <input type="password" name="password_confirmation" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                @php $address = $admin->address; @endphp
                <div>
                    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">Endereço</h2>
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">CEP</label>
                            <input type="text" name="cep" id="cep" value="{{ old('cep', $address?->cep) }}" maxlength="8" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            <x-input-error :messages="$errors->get('cep')" class="mt-1" />
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro" value="{{ old('logradouro', $address?->logradouro) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Número</label>
                            <input type="text" name="numero" value="{{ old('numero', $address?->numero) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Bairro</label>
                            <input type="text" name="bairro" id="bairro" value="{{ old('bairro', $address?->bairro) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Cidade</label>
                            <input type="text" name="cidade" id="cidade" value="{{ old('cidade', $address?->cidade) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">UF</label>
                            <input type="text" name="uf" id="uf" value="{{ old('uf', $address?->uf) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" readonly required>
                            <input type="hidden" name="estado" id="estado" value="{{ old('estado', $address?->estado) }}">
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Complemento</label>
                            <input type="text" name="complemento" value="{{ old('complemento', $address?->complemento) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('admin.admins.index') }}" class="px-6 py-2 rounded-full border border-white/20 text-white/70 hover:text-white hover:border-white/40 transition">Cancelar</a>
                    <button type="submit" class="px-6 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition shadow-lg shadow-emerald-500/20">Atualizar Admin</button>
                </div>
            </form>
        </div>
    </section>
    <x-footer />
    <x-user-modal :auth-user-name="Auth::user()->nome" />
    <script>
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
                        }
                    });
            }
        });
    </script>
</body>
</html>