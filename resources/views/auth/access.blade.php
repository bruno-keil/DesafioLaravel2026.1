@php
    $loginErrors = $errors->getBag('login');
    $registerErrors = $errors->getBag('register');
    $registerPrefill = $registerErrors->any() || old('nome');
    $defaultMode = $mode ?? ($registerPrefill ? 'register' : 'login');
    $registerNameValue = $registerPrefill ? old('nome') : '';
    $registerEmailValue = $registerPrefill ? old('email') : '';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>LootBay | Acesso</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/welcome.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#070b11] text-[#f4f7fb] font-['Manrope']">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0f1827] via-[#070b11] to-black"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_20%,_rgba(56,211,159,0.18),_transparent_45%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_0%,_rgba(59,130,246,0.18),_transparent_40%)]"></div>

        <div class="relative z-10 mx-auto w-[min(1100px,94vw)] py-10">
            <nav class="flex flex-wrap items-center justify-between gap-4 text-[0.7rem] uppercase tracking-[0.3em] text-white/60">
                <a class="flex items-center gap-3 text-white" href="/">
                    <img src="{{ asset('logo.png') }}" alt="LootBay Logo" class="h-12 w-24">
                </a>
                <a class="transition hover:text-white" href="/">Voltar para a home</a>
            </nav>

            <div class="mt-10" x-data="{ mode: '{{ $defaultMode }}' }">
                <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                    <section class="rounded-3xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                        <div class="text-[0.7rem] uppercase tracking-[0.4em] text-emerald-300">Acesso</div>
                        <h1 class="mt-4 font-['Bebas_Neue'] text-[clamp(2.6rem,6vw,4.2rem)] uppercase tracking-[0.12em]">
                            Entre ou crie sua conta
                        </h1>
                        <p class="mt-3 text-base text-white/70">
                            Compre e venda eletronicos direto com a comunidade LootBay.
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <button type="button"
                                    class="rounded-full px-5 py-2 text-[0.7rem] font-semibold uppercase tracking-[0.2em] transition"
                                    :class="mode === 'login' ? 'bg-emerald-400 text-[#081018]' : 'border border-white/20 text-white/70'"
                                    @click="mode = 'login'">
                                Login
                            </button>
                            <button type="button"
                                    class="rounded-full px-5 py-2 text-[0.7rem] font-semibold uppercase tracking-[0.2em] transition"
                                    :class="mode === 'register' ? 'bg-emerald-400 text-[#081018]' : 'border border-white/20 text-white/70'"
                                    @click="mode = 'register'">
                                Registrar
                            </button>
                        </div>

                        <div class="mt-8 grid gap-4 text-sm text-white/60">
                            <div class="flex items-start gap-3">
                                <span class="mt-2 h-2 w-2 rounded-full bg-emerald-400"></span>
                                <p>Negociacao direta com vendedores e compradores reais.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-2 h-2 w-2 rounded-full bg-emerald-400"></span>
                                <p>Itens verificados com fotos, descricao e preco transparente.</p>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="mt-2 h-2 w-2 rounded-full bg-emerald-400"></span>
                                <p>Avaliacoes publicas para manter a comunidade segura.</p>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-white/10 bg-[#0c121b]/80 p-8 backdrop-blur">
                        <div class="relative overflow-hidden">
                            <div class="flex w-[200%] transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)]"
                                 :class="mode === 'login' ? 'translate-x-0' : '-translate-x-1/2'"
                                 x-cloak>
                                <div class="w-1/2 pr-6">
                                    <div class="text-[0.7rem] uppercase tracking-[0.4em] text-white/50">Login</div>
                                    <h2 class="mt-2 font-['Bebas_Neue'] text-[clamp(2rem,4vw,2.8rem)] uppercase tracking-[0.12em]">
                                        Bem-vindo de volta
                                    </h2>
                                    <x-auth-session-status class="mt-4 text-emerald-300" :status="session('status')" />

                                    <form class="mt-6 grid gap-4" method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div>
                                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="login-email">Email</label>
                                            <input id="login-email"
                                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                                   type="email"
                                                   name="email"
                                                   value="{{ old('email') }}"
                                                   required
                                                   @if ($defaultMode === 'login') autofocus @endif
                                                   autocomplete="username" />
                                            <x-input-error class="mt-2 text-xs" :messages="$loginErrors->get('email')" />
                                        </div>

                                        <div>
                                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="login-password">Senha</label>
                                            <input id="login-password"
                                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                                   type="password"
                                                   name="password"
                                                   required
                                                   autocomplete="current-password" />
                                            <x-input-error class="mt-2 text-xs" :messages="$loginErrors->get('password')" />
                                        </div>

                                        <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-white/60">
                                            <label class="inline-flex items-center gap-2">
                                                <input id="remember_me"
                                                       type="checkbox"
                                                       name="remember"
                                                       class="h-4 w-4 rounded border-white/30 bg-white/10 text-emerald-400 focus:ring-emerald-400/40">
                                                <span>Lembrar de mim</span>
                                            </label>

                                            @if (Route::has('password.request'))
                                                <a class="text-emerald-300 transition hover:text-emerald-200" href="{{ route('password.request') }}">
                                                    Esqueceu a senha?
                                                </a>
                                            @endif
                                        </div>

                                        <button type="submit"
                                                class="mt-2 inline-flex items-center justify-center rounded-full bg-emerald-400 px-6 py-3 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:brightness-110">
                                            Entrar
                                        </button>
                                    </form>
                                </div>

                                <div class="w-1/2 pl-6">
                                    <div class="text-[0.7rem] uppercase tracking-[0.4em] text-white/50">Registrar</div>
                                    <h2 class="mt-2 font-['Bebas_Neue'] text-[clamp(2rem,4vw,2.8rem)] uppercase tracking-[0.12em]">
                                        Crie sua conta
                                    </h2>

                                    <form class="mt-6 grid gap-4" method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <div>
                                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="register-nome">Nome</label>
                                            <input id="register-nome"
                                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                                   type="text"
                                                   name="nome"
                                                   value="{{ $registerNameValue }}"
                                                   required
                                                   @if ($defaultMode === 'register') autofocus @endif
                                                   autocomplete="name" />
                                            <x-input-error class="mt-2 text-xs" :messages="$registerErrors->get('nome')" />
                                        </div>

                                        <div>
                                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="register-email">Email</label>
                                            <input id="register-email"
                                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                                   type="email"
                                                   name="email"
                                                   value="{{ $registerEmailValue }}"
                                                   required
                                                   autocomplete="username" />
                                            <x-input-error class="mt-2 text-xs" :messages="$registerErrors->get('email')" />
                                        </div>

                                        <div>
                                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="register-password">Senha</label>
                                            <input id="register-password"
                                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                                   type="password"
                                                   name="password"
                                                   required
                                                   autocomplete="new-password" />
                                            <x-input-error class="mt-2 text-xs" :messages="$registerErrors->get('password')" />
                                        </div>

                                        <div>
                                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="register-password-confirmation">Confirmar senha</label>
                                            <input id="register-password-confirmation"
                                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                                   type="password"
                                                   name="password_confirmation"
                                                   required
                                                   autocomplete="new-password" />
                                            <x-input-error class="mt-2 text-xs" :messages="$registerErrors->get('password_confirmation')" />
                                        </div>

                                        <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-white/60">
                                            <span>Ja tem conta?</span>
                                            <button type="button"
                                                    class="text-emerald-300 transition hover:text-emerald-200"
                                                    @click="mode = 'login'">
                                                Entrar agora
                                            </button>
                                        </div>

                                        <button type="submit"
                                                class="mt-2 inline-flex items-center justify-center rounded-full bg-emerald-400 px-6 py-3 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:brightness-110">
                                            Criar conta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
