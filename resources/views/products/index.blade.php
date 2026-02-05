<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LootBay - Anuncios</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/ts/product-index.ts'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">

    <header class="relative overflow-hidden py-12">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 to-black/90"></div>
        <div class="relative z-10 mx-auto w-[min(1140px,92vw)]">
            <x-navbar :is-authenticated="$isAuthenticated" :auth-user-name="$authUserName" />

            <div class="mt-10">
                <h1 class="font-['Bebas_Neue'] text-[clamp(3rem,6vw,4.2rem)] uppercase tracking-[0.12em]">Anuncios P2P</h1>
                <p class="mt-2 text-white/70">Escolha uma categoria para ver anuncios de outros usuarios.</p>
                @php
                    $searchQuery = $searchTerm ? ['busca' => $searchTerm] : [];
                @endphp
                <form class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center" action="{{ route('products.index') }}" method="GET">
                    @if (!empty($categoryId))
                        <input type="hidden" name="categoria" value="{{ $categoryId }}">
                    @endif
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i>
                        <input class="w-full rounded-full border border-white/10 bg-black/40 px-11 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/70 focus:outline-none" name="busca" placeholder="Busque por nome ou descricao" value="{{ $searchTerm }}" aria-label="Buscar anuncios">
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button class="rounded-full bg-emerald-400 px-5 py-3 text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:bg-emerald-300" type="submit">Buscar</button>
                        @if ($searchTerm)
                            <a class="rounded-full border border-white/20 px-5 py-3 text-[0.7rem] uppercase tracking-[0.2em] text-white/70 transition hover:border-emerald-400/60" href="{{ route('products.index', $categoryId ? ['categoria' => $categoryId] : []) }}">Limpar</a>
                        @endif
                    </div>
                </form>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a class="rounded-full border px-4 py-2 text-[0.7rem] uppercase tracking-[0.2em] {{ empty($categoryId) ? 'border-transparent bg-emerald-400 text-[#081018]' : 'border-white/20 text-white/70' }}" href="{{ route('products.index', $searchQuery) }}">Todos</a>
                    @foreach ($categories as $category)
                        <a class="rounded-full border px-4 py-2 text-[0.7rem] uppercase tracking-[0.2em] {{ (string) $categoryId === (string) $category->id ? 'border-transparent bg-emerald-400 text-[#081018]' : 'border-white/20 text-white/70' }}" href="{{ route('products.index', array_merge(['categoria' => $category->id], $searchQuery)) }}">
                            {{ $category->nome }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    <section class="py-14">
        <div class="mx-auto w-[min(1140px,92vw)]">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($products as $product)
                    <a class="group block rounded-2xl border border-white/10 bg-white/5 transition hover:-translate-y-2 hover:border-emerald-400/50" href="{{ route('products.show', $product) }}">
                        <div class="aspect-[4/3] w-full rounded-t-2xl bg-cover bg-center" style="background-image: url('{{ $product->display_photo }}');"></div>
                        <div class="p-4">
                            <div class="text-[0.7rem] uppercase tracking-[0.2em] text-white/50">{{ $product->category?->nome ?? 'Loot' }}</div>
                            <div class="mt-2 text-base font-semibold text-white">{{ $product->nome }}</div>
                            <div class="mt-2 text-sm font-semibold text-emerald-300">R$ {{ number_format((float) $product->preco, 2, ',', '.') }}</div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-white/60">Nenhum anuncio encontrado.</div>
                @endforelse
            </div>

            <div class="mt-10 flex items-center justify-center gap-4 text-[0.7rem] uppercase tracking-[0.2em] text-white/50">
                @if ($products->onFirstPage())
                    <span>Anterior</span>
                @else
                    <a class="rounded-full border border-white/20 px-4 py-2 transition hover:border-emerald-400/60" href="{{ $products->previousPageUrl() }}">Anterior</a>
                @endif

                @if ($products->hasMorePages())
                    <a class="rounded-full border border-white/20 px-4 py-2 transition hover:border-emerald-400/60" href="{{ $products->nextPageUrl() }}">Proximo</a>
                @else
                    <span>Proximo</span>
                @endif
            </div>
        </div>
    </section>

    <x-footer />

    @if ($isAuthenticated)
        <x-user-modal :auth-user-name="$authUserName" />
    @endif
</body>
</html>
