<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>LootBay | Redefinir senha</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bebas-neue:400|manrope:300,400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#070b11] text-[#f4f7fb] font-['Manrope']">
    <div class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-[#0f1827] via-[#070b11] to-black"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,_rgba(56,211,159,0.2),_transparent_45%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_0%,_rgba(59,130,246,0.18),_transparent_40%)]"></div>

        <div class="relative z-10 mx-auto flex min-h-screen w-[min(960px,92vw)] flex-col py-10">
            <nav class="flex flex-wrap items-center justify-between gap-4 text-[0.7rem] uppercase tracking-[0.3em] text-white/60">
                <a class="flex items-center gap-3 text-white" href="/">
                    <img src="{{ asset('logo.png') }}" alt="LootBay Logo" class="h-20 w-32">
                </a>
                <a class="transition hover:text-white" href="{{ route('login') }}">Voltar ao login</a>
            </nav>

            <div class="flex flex-1 items-center justify-center">
                <section class="w-full max-w-xl rounded-3xl border border-white/10 bg-[#0c121b]/80 p-8 backdrop-blur">
                    <div class="text-[0.7rem] uppercase tracking-[0.4em] text-emerald-300">Recuperacao</div>
                    <h1 class="mt-4 font-['Bebas_Neue'] text-[clamp(2.4rem,5vw,3.4rem)] uppercase tracking-[0.12em]">
                        Redefinir senha
                    </h1>
                    <p class="mt-3 text-base text-white/70">
                        Defina uma nova senha para acessar sua conta.
                    </p>

                    <form class="mt-6 grid gap-4" method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div>
                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="email">Email</label>
                            <input id="email"
                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                   type="email"
                                   name="email"
                                   value="{{ old('email', $request->email) }}"
                                   required
                                   readonly
                                   autofocus
                                   autocomplete="username" />
                            <x-input-error class="mt-2 text-xs text-rose-300" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="password">Nova senha</label>
                            <input id="password"
                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="new-password" />
                            <x-input-error class="mt-2 text-xs text-rose-300" :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <label class="text-[0.7rem] uppercase tracking-[0.3em] text-white/50" for="password_confirmation">Confirmar senha</label>
                            <input id="password_confirmation"
                                   class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white placeholder-white/40 focus:border-emerald-400/60 focus:outline-none focus:ring-2 focus:ring-emerald-400/20"
                                   type="password"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password" />
                            <x-input-error class="mt-2 text-xs text-rose-300" :messages="$errors->get('password_confirmation')" />
                        </div>

                        <div class="flex flex-wrap justify-between gap-3 pt-2">
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-emerald-400 px-6 py-3 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:brightness-110">
                                Redefinir senha
                            </button>
                            <a class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/5 px-6 py-3 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-white/80 transition hover:border-white/40 hover:text-white"
                               href="{{ route('login') }}">
                                Voltar ao login
                            </a>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
