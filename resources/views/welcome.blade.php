<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LootBay | P2P Marketplace</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/css/welcome.css', 'resources/ts/welcome.ts'])
</head>
<body class="bg-[#0a0f16] text-[#f4f7fb] font-['Manrope']">
    <header class="relative min-h-[60vh] py-10 overflow-hidden" data-carousel style="--hero-image: url('{{ $firstSlide['image'] }}');">
        <div class="absolute inset-0 bg-cover bg-center scale-105" style="background-image: var(--hero-image);"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/75 to-black/95"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_15%,_rgba(56,211,159,0.25),_transparent_45%)]"></div>

        <div class="relative z-10">
            <x-navbar :is-authenticated="$isAuthenticated" :auth-user-name="$authUserName" />

            <div class="mx-auto mt-6 w-[min(1140px,92vw)]">
                <div class="h-px bg-white/20"></div>
            </div>

            <div class="mx-auto mt-10 grid w-[min(1140px,92vw)] gap-10 lg:grid-cols-[1.2fr_0.4fr] lg:items-center">
                <div>
                    <div class="flex items-center gap-2 text-[0.7rem] uppercase tracking-[0.4em] text-emerald-300 transition duration-300" data-hero-tag>{{ $firstSlide['tag'] }}</div>
                    <h1 class="mt-4 font-['Bebas_Neue'] text-[clamp(3.5rem,10vw,7rem)] leading-[0.88] uppercase tracking-[0.12em] transition duration-300" data-hero-title>{{ $firstSlide['title'] }}</h1>
                    <p class="mt-4 max-w-xl text-base text-white/70 transition duration-300" data-hero-sub>{{ $firstSlide['subtitle'] }}</p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <a class="inline-flex items-center justify-center rounded-full bg-emerald-400 px-6 py-3 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:brightness-110" data-hero-link href="{{ $firstSlide['link'] }}">Ver mais</a>
                        <a class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/5 px-6 py-3 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-white/80 transition hover:text-white" href="{{ route('products.index') }}">Ver produtos</a>
                    </div>
                </div>
                <div class="grid gap-4 justify-items-end text-sm text-white/60" data-hero-index>
                    @foreach ($slides as $index => $slide)
                        <button type="button" class="transition text-white/60 data-[active=true]:text-white data-[active=true]:text-2xl data-[active=true]:font-semibold" data-index="{{ $index }}" data-active="{{ $index === 0 ? 'true' : 'false' }}">
                            {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    <section class="py-16" id="produtos">
        <div class="mx-auto w-[min(1140px,92vw)]">
            <div class="text-center">
                <p class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50">anuncios recentes</p>
                <h2 class="mt-2 font-['Bebas_Neue'] text-[clamp(2.2rem,4vw,3rem)] uppercase tracking-[0.12em]">Itens de outros usuarios</h2>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
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
        </div>
    </section>

    <x-footer id="footer" />

    @if ($isAuthenticated)
        <x-user-modal :auth-user-name="$authUserName" />
    @endif

    <script type="application/json" id="hero-slides-data">@json($slides)</script>
</body>
</html>
