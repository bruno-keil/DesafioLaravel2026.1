<x-layouts.lootbay title="LootBay - Minhas Compras">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 mx-auto w-[min(1140px,92vw)]">
                <a href="{{ route('dashboard') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar ao Dashboard</a>

                <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                    <div>
                        <h1 class="font-['Bebas_Neue'] text-[clamp(2rem,5vw,3rem)] uppercase tracking-[0.12em] text-white">Minhas Compras</h1>
                        <p class="text-white/60">Histórico de compras dos seus produtos.</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-10">
        <div class="mx-auto w-[min(1140px,92vw)]">
            @if($purchases->isEmpty())
                <div class="rounded-2xl border border-white/10 bg-white/5 p-12 text-center">
                    <i class="bi bi-receipt-cutoff text-5xl text-white/20 mb-4 block"></i>
                    <p class="text-white/40 text-lg">Você ainda não realizou nenhuma compra.</p>
                    <a href="{{ route('products.my') }}" class="mt-4 inline-block px-6 py-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold transition shadow-lg shadow-emerald-500/20">
                        <i class="bi bi-tags mr-2"></i>Gerenciar Produtos
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($purchases as $purchase)
                        <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
                            <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4 bg-white/5 border-b border-white/10">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <span class="text-xs text-white/40 uppercase tracking-wider">Compra #{{ $purchase->id }}</span>
                                        <p class="text-white font-semibold">{{ $purchase->data->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <span class="text-xs text-white/40 uppercase tracking-wider">Comprador</span>
                                        <p class="text-white">{{ $purchase->buyer->nome ?? 'Usuário removido' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs text-white/40 uppercase tracking-wider">Total (seus itens)</span>
                                        <p class="text-emerald-400 font-bold text-lg">
                                            R$ {{ number_format($purchase->items->sum(fn($item) => $item->quantidade * $item->valor_unitario), 2, ',', '.') }}
                                        </p>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'pago' => 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400',
                                            'pendente' => 'bg-yellow-500/10 border-yellow-500/30 text-yellow-400',
                                            'cancelado' => 'bg-red-500/10 border-red-500/30 text-red-400',
                                        ];
                                        $color = $statusColors[strtolower($purchase->status)] ?? 'bg-white/10 border-white/20 text-white/60';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full border text-xs font-bold uppercase tracking-wider {{ $color }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-white/70">
                                    <thead class="text-xs uppercase tracking-wider text-white/40 border-b border-white/5">
                                        <tr>
                                            <th class="px-6 py-3">Produto</th>
                                            <th class="px-6 py-3">Qtd</th>
                                            <th class="px-6 py-3">Valor Unitário</th>
                                            <th class="px-6 py-3 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($purchase->items as $item)
                                            <tr class="hover:bg-white/5 transition">
                                                <td class="px-6 py-3 text-white font-medium flex items-center gap-3">
                                                    @if($item->product)
                                                        <img class="h-10 w-10 rounded object-cover shrink-0" src="{{ $item->product->display_photo }}" alt="{{ $item->product->nome }}">
                                                        <a href="{{ route('products.show', $item->product) }}" class="hover:underline">{{ $item->product->nome }}</a>
                                                    @else
                                                        <span class="text-white/40 italic">Produto removido</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-3">{{ $item->quantidade }}</td>
                                                <td class="px-6 py-3">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                                <td class="px-6 py-3 text-right text-emerald-400">R$ {{ number_format($item->quantidade * $item->valor_unitario, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.lootbay>
