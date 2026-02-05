<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LootBay - Carrinho</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/welcome.css', 'resources/ts/cart.ts'])
</head>

<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    <header class="relative overflow-hidden py-12">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1400&q=80');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 to-black/90"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="$isAuthenticated" :auth-user-name="$authUserName" />

            <div class="mt-10 mx-auto w-[min(1140px,92vw)]">
                <h1 class="font-['Bebas_Neue'] text-[clamp(3rem,6vw,4.2rem)] uppercase tracking-[0.12em]">Carrinho</h1>
                <p class="mt-2 text-white/70">Revise seus itens e ajuste as quantidades antes de seguir.</p>
            </div>
        </div>
    </header>

    <section class="py-14">
        <div class="mx-auto w-[min(1140px,92vw)] space-y-6">
            @if (session('error'))
            <div class="rounded-xl border border-red-400/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">{{ session('error') }}</div>
            @endif
            @if (session('notice'))
            <div class="rounded-xl border border-amber-400/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-200">{{ session('notice') }}</div>
            @endif
            @if (session('success'))
            <div class="rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{{ session('success') }}</div>
            @endif

            @if (count($items) === 0)
            <div class="rounded-2xl border border-white/10 bg-white/5 p-10 text-center text-white/60">
                Seu carrinho esta vazio.
            </div>
            @else
            <div class="space-y-4">
                @foreach ($items as $item)
                <div class="flex flex-col gap-4 rounded-2xl border border-white/10 bg-white/5 p-4 md:flex-row md:items-center md:justify-between" data-cart-item data-price="{{ $item['price'] }}" data-max="{{ max(1, (int) $item['stock']) }}">
                    <div class="flex items-center gap-4">
                        <div class="h-20 w-24 rounded-xl bg-cover bg-center" data-photo="{{ $item['photo'] }}"></div>
                        <div>
                            <div class="text-[0.7rem] uppercase tracking-[0.2em] text-white/50">{{ $item['category'] }}</div>
                            <div class="mt-1 text-base font-semibold text-white">{{ $item['name'] }}</div>
                            <div class="mt-1 text-sm text-emerald-300">R$ {{ number_format((float) $item['price'], 2, ',', '.') }}</div>
                            <div class="mt-1 text-xs text-white/50">Total: <span data-line-total>R$ {{ number_format((float) ($item['price'] * $item['quantity']), 2, ',', '.') }}</span></div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <form class="flex items-center gap-3" method="post" action="{{ route('cart.update', $item['product_id']) }}" data-cart-form>
                            @csrf
                            @method('PATCH')
                            <div class="inline-flex items-center gap-3 rounded-full border border-white/20 bg-white/5 px-3 py-2" data-qty>
                                <button type="button" class="h-8 w-8 rounded-full border border-white/20 text-white/80 transition hover:border-emerald-400/60" data-minus>-</button>
                                <input class="w-16 bg-transparent text-center text-white focus:outline-none" type="number" min="1" max="{{ max(1, (int) $item['stock']) }}" name="quantity" value="{{ $item['quantity'] }}" data-input>
                                <button type="button" class="h-8 w-8 rounded-full border border-white/20 text-white/80 transition hover:border-emerald-400/60" data-plus>+</button>
                            </div>
                        </form>
                        <form method="post" action="{{ route('cart.remove', $item['product_id']) }}">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-full border border-white/20 px-4 py-2 text-[0.7rem] uppercase tracking-[0.2em] text-white/70 transition hover:border-red-400/60 hover:text-white" type="submit">Remover</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex flex-col items-start justify-between gap-4 rounded-2xl border border-white/10 bg-white/5 p-6 md:flex-row md:items-center" data-cart-summary>
                <div class="text-sm text-white/60">Subtotal</div>
                <div class="text-2xl font-semibold text-emerald-300" data-subtotal>R$ {{ number_format((float) $subtotal, 2, ',', '.') }}</div>
            </div>
            <div class="flex flex-col items-center justify-center gap-4 rounded-2xl border border-white/10 bg-white/5 p-6 md:flex-row md:items-center" data-cart-summary>
                <a href="{{ route('checkout.address') }}" class="rounded-full border border-white/20 px-8 py-3 text-[0.8rem] uppercase tracking-[0.2em] text-emerald-300 transition hover:bg-emerald-500 hover:text-white hover:border-emerald-500 shadow-lg hover:shadow-emerald-500/20">
                    Checkout
                </a>
            </div>
            @endif
        </div>
    </section>

    <x-footer />

    @if ($isAuthenticated)
    <x-user-modal :auth-user-name="$authUserName" />
    @endif
</body>

</html>