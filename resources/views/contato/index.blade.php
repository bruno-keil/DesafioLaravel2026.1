<x-layouts.lootbay title="LootBay - Contato">
    <header class="relative overflow-hidden py-12">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1534536281715-e28d76689b4d?auto=format&fit=crop&w=1400&q=80');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 to-black/90"></div>
        <div class="relative z-10 mx-auto w-[min(1140px,92vw)]">
            <x-navbar :is-authenticated="$isAuthenticated" :auth-user-name="$authUserName" />

            <div class="mt-10">
                <h1 class="font-['Bebas_Neue'] text-[clamp(3rem,6vw,4.2rem)] uppercase tracking-[0.12em]">Fale Conosco</h1>
                <p class="mt-2 text-white/70">Envie sua mensagem, duvida ou reclamacao. Responderemos o mais breve possivel.</p>
            </div>
        </div>
    </header>

    <section class="py-14">
        <div class="mx-auto w-[min(720px,92vw)]">
            @if (session('success'))
                <div class="mb-8 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-6 py-4 text-sm text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-2xl border border-white/10 bg-white/5 p-8">
                <form action="{{ route('contato.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="nome" class="mb-1.5 block text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-white/60">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome', auth()->user()?->nome) }}" required autofocus
                            class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white placeholder-white/30 focus:border-emerald-400/70 focus:outline-none focus:ring-1 focus:ring-emerald-400/40">
                        @error('nome')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-white/60">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()?->email) }}" required
                            class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white placeholder-white/30 focus:border-emerald-400/70 focus:outline-none focus:ring-1 focus:ring-emerald-400/40">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assunto" class="mb-1.5 block text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-white/60">Assunto</label>
                        <input type="text" name="assunto" id="assunto" value="{{ old('assunto') }}" required
                            class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white placeholder-white/30 focus:border-emerald-400/70 focus:outline-none focus:ring-1 focus:ring-emerald-400/40">
                        @error('assunto')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mensagem" class="mb-1.5 block text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-white/60">Mensagem</label>
                        <textarea name="mensagem" id="mensagem" rows="6" required
                            class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white placeholder-white/30 focus:border-emerald-400/70 focus:outline-none focus:ring-1 focus:ring-emerald-400/40">{{ old('mensagem') }}</textarea>
                        @error('mensagem')
                            <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="rounded-full bg-emerald-400 px-8 py-3 text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:bg-emerald-300">
                            Enviar Mensagem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layouts.lootbay>