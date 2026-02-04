@props([
    'brandImage' => asset('logo.png'),
    'brandImageAlt' => 'LootBay Logo',
    'brandImageClass' => 'h-20 w-32',
    'cartHref' => null,
    'isAuthenticated' => false,
    'authUserName' => null,
])

@php
    $cartHref = $cartHref ?? route('cart.index');
@endphp

<nav {{ $attributes->merge(['class' => 'mx-auto w-[min(1140px,92vw)] flex items-center justify-between gap-6 text-[0.7rem] uppercase tracking-[0.2em]']) }}>
    <div class="flex items-center gap-3 font-semibold">
        <img src="{{ $brandImage }}" alt="{{ $brandImageAlt }}" class="{{ $brandImageClass }}">
    </div>

    <div class="hidden items-center gap-6 text-white/60 md:flex">
        <a href="{{ url('/') }}" class="text-white/80 transition hover:text-white">Home</a>
        <a href="{{ route('products.index') }}" class="text-white/80 transition hover:text-white">Produtos</a>
    </div>

    <div class="flex items-center gap-3">
        @if (isset($links) && ! $links->isEmpty())
            {{ $links }}
        @endif

        <a class="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/5 text-white/80 transition hover:text-white" href="{{ $cartHref }}" aria-label="Carrinho">
            <i class="bi bi-cart text-base" aria-hidden="true"></i>
        </a>

        @if (! $isAuthenticated)
            <a class="rounded-full border border-white/20 bg-white/5 px-4 py-2 font-semibold text-white/90 transition hover:border-white/40" href="/login">Login</a>
            <a class="rounded-full border border-white/20 bg-white/5 px-4 py-2 font-semibold text-white/90 transition hover:border-white/40" href="/register">Registrar</a>
        @else
            <button type="button" data-user-trigger class="max-w-[160px] truncate rounded-full border border-white/20 bg-white/5 px-4 py-2 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-white/80 transition hover:text-white">
                {{ $authUserName }}
            </button>
        @endif
    </div>
</nav>
