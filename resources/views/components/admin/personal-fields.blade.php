@props(['model' => null])

<div>
    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">Dados Pessoais</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="form-label-dark">Nome Completo</label>
            <input type="text" name="nome" value="{{ old('nome', $model?->nome) }}" class="form-input-dark" required>
            <x-input-error :messages="$errors->get('nome')" class="mt-1" />
        </div>
        <div>
            <label class="form-label-dark">Email</label>
            <input type="email" name="email" value="{{ old('email', $model?->email) }}" class="form-input-dark" required>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>
        <div>
            <label class="form-label-dark">CPF</label>
            <input type="text" name="cpf" value="{{ old('cpf', $model?->cpf) }}" class="form-input-dark" placeholder="000.000.000-00" required>
            <x-input-error :messages="$errors->get('cpf')" class="mt-1" />
        </div>
        <div>
            <label class="form-label-dark">Data de Nascimento</label>
            <input type="date" name="data_nascimento" value="{{ old('data_nascimento', $model?->data_nascimento?->format('Y-m-d')) }}" class="form-input-dark" required>
            <x-input-error :messages="$errors->get('data_nascimento')" class="mt-1" />
        </div>
        <div>
            <label class="form-label-dark">Telefone</label>
            <input type="text" name="telefone" value="{{ old('telefone', $model?->telefone) }}" class="form-input-dark" required>
            <x-input-error :messages="$errors->get('telefone')" class="mt-1" />
        </div>
    </div>
</div>
