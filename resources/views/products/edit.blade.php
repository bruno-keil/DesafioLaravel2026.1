<x-layouts.lootbay title="LootBay - Editar Produto">
    <header class="relative overflow-hidden py-8">
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 container-main">
                @if(Auth::user()->is_admin)
                     <a href="{{ route('admin.products.index') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar para lista de produtos</a>
                @else
                    <a href="{{ route('products.my') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar para meus produtos</a>
                @endif
                <h1 class="page-title">Editar Produto</h1>
            </div>
        </div>
    </header>

    <section class="py-10 pb-20">
        <div class="container-main">
            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="glass-card p-8 backdrop-blur-sm space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1">
                        <label class="form-label-dark mb-2">Foto do Produto</label>
                        <div class="relative aspect-square rounded-2xl border-2 border-dashed border-white/20 hover:border-emerald-500/50 bg-black/20 transition flex flex-col items-center justify-center cursor-pointer group overflow-hidden">
                            <input type="file" name="foto" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this)" accept="image/*">
                            <img id="preview" src="{{ $product->display_photo }}" class="absolute inset-0 w-full h-full object-cover">
                            <div id="placeholder" class="text-center p-4 group-hover:scale-105 transition hidden">
                                <i class="bi bi-camera text-3xl text-white/30 group-hover:text-emerald-400 mb-2 block"></i>
                                <span class="text-sm text-white/50 group-hover:text-white">Clique para alterar</span>
                            </div>
                        </div>
                        <p class="text-xs text-white/40 mt-2 text-center">Deixe em branco para manter a foto atual.</p>
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <label class="form-label-dark">Nome do Produto</label>
                            <input type="text" name="nome" value="{{ old('nome', $product->nome) }}" class="form-input-dark" required>
                            <x-input-error :messages="$errors->get('nome')" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label-dark">Categoria</label>
                                <select name="categoria_id" class="form-input-dark appearance-none">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('categoria_id', $product->categoria_id) == $category->id ? 'selected' : '' }} class="bg-[#0a0f16]">{{ $category->nome }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('categoria_id')" class="mt-1" />
                            </div>
                            <div>
                                <label class="form-label-dark">Preço (R$)</label>
                                <input type="number" name="preco" step="0.01" value="{{ old('preco', $product->preco) }}" class="form-input-dark" required>
                                <x-input-error :messages="$errors->get('preco')" class="mt-1" />
                            </div>
                        </div>

                        <div>
                            <label class="form-label-dark">Quantidade em Estoque</label>
                            <input type="number" name="quantidade" value="{{ old('quantidade', $product->quantidade) }}" class="form-input-dark" required>
                            <x-input-error :messages="$errors->get('quantidade')" class="mt-1" />
                        </div>

                        <div>
                            <label class="form-label-dark">Descrição</label>
                            <textarea name="descricao" rows="4" class="form-input-dark" required>{{ old('descricao', $product->descricao) }}</textarea>
                            <x-input-error :messages="$errors->get('descricao')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-white/10">
                    <a href="{{ Auth::user()->is_admin ? route('admin.products.index') : route('products.my') }}" class="btn-ghost">Cancelar</a>
                    <button type="submit" class="btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </section>
    <x-slot:scripts>
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('preview').src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </x-slot:scripts>
</x-layouts.lootbay>