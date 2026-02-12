@props(['address' => null])

<div>
    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">Endereço</h2>
    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
        <div class="md:col-span-2">
            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">CEP</label>
            <input type="text" name="cep" id="cep" value="{{ old('cep', $address?->cep) }}" maxlength="8" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" placeholder="00000000" required>
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
