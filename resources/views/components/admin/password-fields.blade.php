@props(['optional' => false])

<div>
    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">{{ $optional ? 'Segurança (Opcional)' : 'Segurança' }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">{{ $optional ? 'Nova Senha' : 'Senha' }}</label>
            <input type="password" name="password" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" {{ $optional ? '' : 'required' }} @if($optional) placeholder="Deixe em branco para manter" @endif>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">{{ $optional ? 'Confirmar Nova Senha' : 'Confirmar Senha' }}</label>
            <input type="password" name="password_confirmation" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" {{ $optional ? '' : 'required' }}>
        </div>
    </div>
</div>
