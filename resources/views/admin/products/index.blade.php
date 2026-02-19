<x-layouts.lootbay title="LootBay - Gerenciar Produtos">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 container-main">
                <a href="{{ route('dashboard') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar ao Dashboard</a>
                
                <h1 class="page-title">Todos os Produtos</h1>
                <p class="text-white/60">Moderação de produtos da plataforma.</p>
            </div>
        </div>
    </header>

    <section class="py-10">
        <div class="container-main">
            @if (session('success'))
                <div class="mb-6 alert-success">{{ session('success') }}</div>
            @endif

            <div class="glass-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead class="data-table-head">
                            <tr>
                                <th class="px-6 py-4">Produto</th>
                                <th class="px-6 py-4">Vendedor</th>
                                <th class="px-6 py-4">Preço</th>
                                <th class="px-6 py-4">Estoque</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach ($products as $product)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 font-medium text-white flex items-center gap-3">
                                        <img class="h-10 w-10 rounded object-cover shrink-0" src="{{ $product->display_photo }}" alt="{{ $product->nome }}">
                                        <div>
                                            <a href="{{ route('products.show', $product) }}" class="hover:underline block">{{ $product->nome }}</a>
                                            <span class="text-[0.65rem] text-white/40 uppercase">{{ $product->category->nome ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $product->user->nome ?? 'Desconhecido' }}
                                        <br>
                                        <span class="text-[0.65rem] text-white/40">{{ $product->user->email ?? '' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-emerald-400">R$ {{ number_format($product->preco, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4">{{ $product->quantidade }}</td>
                                    <td class="px-6 py-4 text-right flex items-center justify-end gap-3">
                                        <a href="{{ route('products.edit', $product) }}" class="text-blue-400 hover:text-white transition" title="Editar">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('ADMINISTRADOR: Tem certeza que deseja excluir este produto?');">
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
                <div class="p-4 border-t border-white/10">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>
</x-layouts.lootbay>