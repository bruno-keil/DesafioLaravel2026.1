<x-layouts.lootbay title="LootBay - Gerenciar Contatos">
    <header class="relative overflow-hidden py-8">
        <div class="absolute inset-0 bg-gradient-to-b from-black/80 to-[#0a0f16]"></div>
        <div class="relative z-10">
            <x-navbar :is-authenticated="true" :auth-user-name="Auth::user()->nome" />
            <div class="mt-8 mx-auto w-[min(1140px,92vw)]">
                <a href="{{ route('dashboard') }}" class="text-sm text-white/50 hover:text-white transition mb-2 inline-block">&larr; Voltar ao Dashboard</a>
                <h1 class="font-['Bebas_Neue'] text-[clamp(2rem,5vw,3rem)] uppercase tracking-[0.12em] text-white">Contatos &amp; Mensagens</h1>
                <p class="text-white/60">Gerencie mensagens recebidas e envie emails para usuarios cadastrados.</p>
            </div>
        </div>
    </header>

    <section class="py-10">
        <div class="mx-auto w-[min(1140px,92vw)]">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">{{ session('success') }}</div>
            @endif

            {{-- Tabs --}}
            <div class="mb-8 flex gap-1 rounded-xl border border-white/10 bg-white/5 p-1" id="contact-tabs">
                <button type="button" data-tab="mensagens"
                    class="tab-btn flex-1 rounded-lg px-5 py-3 text-[0.7rem] font-semibold uppercase tracking-[0.2em] transition"
                    onclick="switchTab('mensagens')">
                    <i class="bi bi-envelope mr-2"></i>Mensagens Recebidas
                </button>
                <button type="button" data-tab="enviar"
                    class="tab-btn flex-1 rounded-lg px-5 py-3 text-[0.7rem] font-semibold uppercase tracking-[0.2em] transition"
                    onclick="switchTab('enviar')">
                    <i class="bi bi-send mr-2"></i>Enviar Email
                </button>
            </div>

            {{-- Tab: Mensagens Recebidas --}}
            <div id="tab-mensagens" class="tab-content">
                <div class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-white/70">
                            <thead class="bg-white/10 text-xs uppercase tracking-wider text-white">
                                <tr>
                                    <th class="px-6 py-4">Nome / Email</th>
                                    <th class="px-6 py-4">Assunto</th>
                                    <th class="px-6 py-4">Data</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Acoes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @forelse ($contacts as $contact)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-white">{{ $contact->nome }}</span><br>
                                            <span class="text-xs text-white/40">{{ $contact->email }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ $contact->assunto }}</td>
                                        <td class="px-6 py-4">{{ $contact->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            @if ($contact->respondido_em)
                                                <span class="inline-flex items-center rounded-full bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-xs font-medium text-emerald-400">Respondido</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 text-xs font-medium text-amber-400">Pendente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button" onclick="toggleDetail({{ $contact->id }})" class="text-emerald-400 hover:text-white transition text-sm font-medium">
                                                <i class="bi bi-eye mr-1"></i>Ver
                                            </button>
                                        </td>
                                    </tr>
                                    <tr id="detail-{{ $contact->id }}" class="hidden">
                                        <td colspan="5" class="px-6 py-6 bg-white/[0.02]">
                                            <div class="space-y-4 max-w-3xl">
                                                <div>
                                                    <p class="text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-white/40 mb-1">Mensagem</p>
                                                    <p class="text-sm text-white/80 leading-relaxed whitespace-pre-line">{{ $contact->mensagem }}</p>
                                                </div>
                                                @if ($contact->resposta)
                                                    <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/5 p-4">
                                                        <p class="text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-emerald-400/60 mb-1">Resposta ({{ $contact->respondido_em->format('d/m/Y H:i') }})</p>
                                                        <p class="text-sm text-white/70 leading-relaxed whitespace-pre-line">{{ $contact->resposta }}</p>
                                                    </div>
                                                @else
                                                    <form action="{{ route('admin.contato.respond', $contact->id) }}" method="POST" class="space-y-3">
                                                        @csrf
                                                        <textarea name="resposta" rows="3" required placeholder="Escreva sua resposta..."
                                                            class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white placeholder-white/30 focus:border-emerald-400/70 focus:outline-none focus:ring-1 focus:ring-emerald-400/40"></textarea>
                                                        <button type="submit" class="rounded-full bg-emerald-400 px-6 py-2 text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:bg-emerald-300">
                                                            <i class="bi bi-reply mr-1"></i>Enviar Resposta
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-white/40">Nenhuma mensagem recebida.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tab: Enviar Email --}}
            <div id="tab-enviar" class="tab-content hidden">
                <div class="mx-auto max-w-2xl rounded-2xl border border-white/10 bg-white/5 p-8">
                    <form action="{{ route('admin.contato.sendEmail') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="user_id" class="mb-1.5 block text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-white/60">Destinatario</label>
                            <select name="user_id" id="user_id" required
                                class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white focus:border-emerald-400/70 focus:outline-none focus:ring-1 focus:ring-emerald-400/40">
                                <option value="" class="bg-[#0a0f16]">Selecione um usuario...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" class="bg-[#0a0f16]" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->nome }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="assunto" class="mb-1.5 block text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-white/60">Assunto</label>
                            <input type="text" name="assunto" id="assunto" value="{{ old('assunto') }}" required
                                class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white placeholder-white/30 focus:border-emerald-400/70 focus:outline-none focus:ring-1 focus:ring-emerald-400/40"
                                placeholder="Assunto do email">
                            @error('assunto')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mensagem" class="mb-1.5 block text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-white/60">Mensagem</label>
                            <textarea name="mensagem" id="mensagem" rows="8" required
                                class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-white placeholder-white/30 focus:border-emerald-400/70 focus:outline-none focus:ring-1 focus:ring-emerald-400/40"
                                placeholder="Escreva a mensagem...">{{ old('mensagem') }}</textarea>
                            @error('mensagem')
                                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="rounded-full bg-emerald-400 px-8 py-3 text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:bg-emerald-300">
                                <i class="bi bi-send mr-2"></i>Enviar Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <x-slot:scripts>
        <script>
            function switchTab(tab) {
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                document.getElementById('tab-' + tab).classList.remove('hidden');

                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('bg-emerald-400', 'text-[#081018]');
                    btn.classList.add('text-white/60', 'hover:text-white', 'hover:bg-white/10');
                });

                const active = document.querySelector('[data-tab="' + tab + '"]');
                active.classList.add('bg-emerald-400', 'text-[#081018]');
                active.classList.remove('text-white/60', 'hover:text-white', 'hover:bg-white/10');
            }

            switchTab('mensagens');

            function toggleDetail(id) {
                const row = document.getElementById('detail-' + id);
                row.classList.toggle('hidden');
            }
        </script>
    </x-slot:scripts>
</x-layouts.lootbay>