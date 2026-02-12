@props(['model' => null])

<div>
    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">Dados Pessoais</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Nome Completo</label>
            <input type="text" name="nome" value="{{ old('nome', $model?->nome) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
            <x-input-error :messages="$errors->get('nome')" class="mt-1" />
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $model?->email) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">CPF</label>
            <input type="text" name="cpf" value="{{ old('cpf', $model?->cpf) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" placeholder="000.000.000-00" required>
            <x-input-error :messages="$errors->get('cpf')" class="mt-1" />
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Data de Nascimento</label>
            <input type="date" name="data_nascimento" value="{{ old('data_nascimento', $model?->data_nascimento?->format('Y-m-d')) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
            <x-input-error :messages="$errors->get('data_nascimento')" class="mt-1" />
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Telefone</label>
            <input type="text" name="telefone" value="{{ old('telefone', $model?->telefone) }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
            <x-input-error :messages="$errors->get('telefone')" class="mt-1" />
        </div>
    </div>
</div>
