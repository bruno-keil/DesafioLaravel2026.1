<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LootBay - {{ $product->nome }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/ts/product-show.ts'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">

    <header class="relative overflow-hidden py-12">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 to-black/90"></div>
        <div class="relative z-10 mx-auto w-[min(1140px,92vw)]">
            <x-navbar :is-authenticated="$isAuthenticated" :auth-user-name="$authUserName" />

            <div class="mt-10 text-sm text-white/60">
                <a class="text-white/80 hover:text-white" href="/">LootBay</a> / <a class="text-white/80 hover:text-white" href="{{ route('products.index') }}">Produtos</a> / {{ $product->nome }}
            </div>
        </div>
    </header>

    <section class="py-14">
        <div class="mx-auto w-[min(1140px,92vw)]">
            <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                    <div class="aspect-[4/5] w-full bg-cover bg-center" style="background-image: url('{{ $displayPhoto }}');"></div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-6">
                    <div class="text-[0.7rem] uppercase tracking-[0.2em] text-white/50">{{ $product->category?->nome ?? 'Loot' }}</div>
                    <h1 class="mt-3 font-['Bebas_Neue'] text-[clamp(2.2rem,5vw,3rem)] uppercase tracking-[0.12em]">{{ $product->nome }}</h1>
                    <div class="mt-2 text-2xl font-semibold text-emerald-300">R$ {{ number_format((float) $product->preco, 2, ',', '.') }}</div>
                    <div class="mt-4 space-y-2 text-sm text-white/60">
                        <div>Disponivel: {{ $product->quantidade }}</div>
                        <div>Vendedor: {{ $product->user?->nome ?? 'Usuario' }}</div>
                    </div>
                    <div class="my-5 h-px bg-white/10"></div>
                    <p class="text-sm text-white/70">{{ $product->descricao ?? 'Sem descricao cadastrada.' }}</p>
                    <div class="my-5 h-px bg-white/10"></div>
                    <div class="space-y-6">
                        @if ($canPurchase)
                            <form class="space-y-6" method="post" action="{{ route('cart.add', $product) }}">
                                @csrf
                                <div>
                                    <div class="text-[0.7rem] uppercase tracking-[0.2em] text-white/50">Quantidade</div>
                                    <div class="mt-2 inline-flex items-center gap-3 rounded-full border border-white/20 bg-white/5 px-3 py-2" data-qty>
                                        <button type="button" class="h-8 w-8 rounded-full border border-white/20 text-white/80 transition hover:border-emerald-400/60" data-minus>-</button>
                                        <input class="w-16 bg-transparent text-center text-white focus:outline-none" type="number" name="quantity" value="1" min="1" max="{{ max(1, (int) $product->quantidade) }}" data-input>
                                        <button type="button" class="h-8 w-8 rounded-full border border-white/20 text-white/80 transition hover:border-emerald-400/60" data-plus>+</button>
                                    </div>
                                </div>
                                <button class="inline-flex items-center justify-center rounded-full bg-emerald-400 px-6 py-3 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:brightness-110" type="submit">Adicionar ao carrinho</button>
                            </form>
                        @else
                            <div class="text-sm text-white/50">Item indisponivel para compra.</div>
                        @endif
                        <a class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/5 px-6 py-3 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-white/80 transition hover:text-white" href="{{ route('products.index') }}">Voltar</a>
                    </div>
                    @if ($showAdminNote)
                        <div class="mt-4 text-sm text-white/50">Admins nao podem comprar anuncios.</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <x-footer />

    @if ($isAuthenticated)
        <x-user-modal :auth-user-name="$authUserName" />
    @endif
</body>
</html>
