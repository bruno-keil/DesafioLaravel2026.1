<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LootBay - Meus Produtos</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/welcome.css'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 mx-auto w-[min(1140px,92vw)]">
                <a href="{{ route('dashboard') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar ao Dashboard</a>
                
                <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                    <div>
                        <h1 class="font-['Bebas_Neue'] text-[clamp(2rem,5vw,3rem)] uppercase tracking-[0.12em] text-white">Meus Produtos</h1>
                        <p class="text-white/60">Gerencie os produtos que você está vendendo.</p>
                    </div>
                    <a href="{{ route('products.create') }}" class="px-6 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition shadow-lg shadow-emerald-500/20">
                        <i class="bi bi-plus-lg mr-2"></i>Anunciar Produto
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section class="py-10">
        <div class="mx-auto w-[min(1140px,92vw)]">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{{ session('success') }}</div>
            @endif

            <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-white/70">
                        <thead class="bg-white/10 text-xs uppercase tracking-wider text-white">
                            <tr>
                                <th class="px-6 py-4">Produto</th>
                                <th class="px-6 py-4">Categoria</th>
                                <th class="px-6 py-4">Preço</th>
                                <th class="px-6 py-4">Estoque</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach ($products as $product)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 font-medium text-white flex items-center gap-3">
                                        <div class="h-10 w-10 rounded bg-cover bg-center shrink-0" 
                                             style="background-image: url('{{ str_starts_with($product->foto, 'http') ? $product->foto : asset($product->foto) }}')"></div>
                                        <a href="{{ route('products.show', $product) }}" class="hover:underline">{{ $product->nome }}</a>
                                    </td>
                                    <td class="px-6 py-4">{{ $product->category->nome ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-emerald-400">R$ {{ number_format($product->preco, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4">{{ $product->quantidade }}</td>
                                    <td class="px-6 py-4 text-right flex items-center justify-end gap-3">
                                        <a href="{{ route('products.edit', $product) }}" class="text-blue-400 hover:text-white transition" title="Editar">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-white transition" title="Excluir">
                                                <i class="bi bi-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($products->isEmpty())
                    <div class="p-8 text-center text-white/40">Você ainda não tem produtos cadastrados.</div>
                @endif
                <div class="p-4 border-t border-white/10">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>
    <x-footer />
    <x-user-modal :auth-user-name="Auth::user()->nome" />
</body>
</html>