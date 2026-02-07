<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LootBay - Anunciar Produto</title>
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
                <a href="{{ route('products.my') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar para meus produtos</a>
                <h1 class="font-['Bebas_Neue'] text-[clamp(2rem,5vw,3rem)] uppercase tracking-[0.12em] text-white">Anunciar Produto</h1>
            </div>
        </div>
    </header>

    <section class="py-10 pb-20">
        <div class="mx-auto w-[min(1140px,92vw)]">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-white/10 bg-white/5 p-8 backdrop-blur-sm space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="md:col-span-1">
                        <label class="block text-xs uppercase tracking-wider text-white/50 mb-2">Foto do Produto</label>
                        <div class="relative aspect-square rounded-2xl border-2 border-dashed border-white/20 hover:border-emerald-500/50 bg-black/20 transition flex flex-col items-center justify-center cursor-pointer group overflow-hidden">
                            <input type="file" name="foto" class="absolute inset-0 opacity-0 cursor-pointer z-10" onchange="previewImage(this)" required accept="image/*">
                            <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden">
                            <div id="placeholder" class="text-center p-4 group-hover:scale-105 transition">
                                <i class="bi bi-camera text-3xl text-white/30 group-hover:text-emerald-400 mb-2 block"></i>
                                <span class="text-sm text-white/50 group-hover:text-white">Clique para upload</span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                    </div>

                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Nome do Produto</label>
                            <input type="text" name="nome" value="{{ old('nome') }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            <x-input-error :messages="$errors->get('nome')" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Categoria</label>
                                <select name="categoria_id" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500 appearance-none">
                                    <option value="" disabled selected>Selecione...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('categoria_id') == $category->id ? 'selected' : '' }} class="bg-[#0a0f16]">{{ $category->nome }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('categoria_id')" class="mt-1" />
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Preço (R$)</label>
                                <input type="number" name="preco" step="0.01" value="{{ old('preco') }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                                <x-input-error :messages="$errors->get('preco')" class="mt-1" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Quantidade em Estoque</label>
                            <input type="number" name="quantidade" value="{{ old('quantidade') }}" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>
                            <x-input-error :messages="$errors->get('quantidade')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-wider text-white/50 mb-1">Descrição</label>
                            <textarea name="descricao" rows="4" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500" required>{{ old('descricao') }}</textarea>
                            <x-input-error :messages="$errors->get('descricao')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-white/10">
                    <a href="{{ route('products.my') }}" class="px-6 py-2 rounded-full border border-white/20 text-white/70 hover:text-white hover:border-white/40 transition">Cancelar</a>
                    <button type="submit" class="px-6 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition shadow-lg shadow-emerald-500/20">Criar Anúncio</button>
                </div>
            </form>
        </div>
    </section>
    <x-footer />
    <x-user-modal :auth-user-name="Auth::user()->nome" />
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview').classList.remove('hidden');
                    document.getElementById('placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>