@props(['address' => null])

<div>
    <h2 class="text-lg font-semibold text-emerald-400 mb-4 border-b border-white/10 pb-2">Endereço</h2>
    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
        <div class="md:col-span-2">
            <label class="form-label-dark">CEP</label>
            <input type="text" name="cep" id="cep" value="{{ old('cep', $address?->cep) }}" maxlength="8" class="form-input-dark" placeholder="00000000" required>
            <x-input-error :messages="$errors->get('cep')" class="mt-1" />
        </div>
        <div class="md:col-span-3">
            <label class="form-label-dark">Logradouro</label>
            <input type="text" name="logradouro" id="logradouro" value="{{ old('logradouro', $address?->logradouro) }}" class="form-input-dark" required>
        </div>
        <div class="md:col-span-1">
            <label class="form-label-dark">Número</label>
            <input type="text" name="numero" value="{{ old('numero', $address?->numero) }}" class="form-input-dark" required>
        </div>
        <div class="md:col-span-2">
            <label class="form-label-dark">Bairro</label>
            <input type="text" name="bairro" id="bairro" value="{{ old('bairro', $address?->bairro) }}" class="form-input-dark" required>
        </div>
        <div class="md:col-span-2">
            <label class="form-label-dark">Cidade</label>
            <input type="text" name="cidade" id="cidade" value="{{ old('cidade', $address?->cidade) }}" class="form-input-dark" required>
        </div>
        <div class="md:col-span-1">
            <label class="form-label-dark">UF</label>
            <input type="text" name="uf" id="uf" value="{{ old('uf', $address?->uf) }}" class="form-input-dark" readonly required>
            <input type="hidden" name="estado" id="estado" value="{{ old('estado', $address?->estado) }}">
        </div>
        <div class="md:col-span-6">
            <label class="form-label-dark">Complemento</label>
            <input type="text" name="complemento" value="{{ old('complemento', $address?->complemento) }}" class="form-input-dark">
        </div>
    </div>
</div>
