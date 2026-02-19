@props(['optional' => false])

<div>
    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">{{ $optional ? 'Segurança (Opcional)' : 'Segurança' }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="form-label-dark">{{ $optional ? 'Nova Senha' : 'Senha' }}</label>
            <input type="password" name="password" class="form-input-dark" {{ $optional ? '' : 'required' }} @if($optional) placeholder="Deixe em branco para manter" @endif>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>
        <div>
            <label class="form-label-dark">{{ $optional ? 'Confirmar Nova Senha' : 'Confirmar Senha' }}</label>
            <input type="password" name="password_confirmation" class="form-input-dark" {{ $optional ? '' : 'required' }}>
        </div>
    </div>
</div>
