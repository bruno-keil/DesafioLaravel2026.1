@props([
    'authUserName' => null,
])

<div data-user-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="w-[min(320px,90vw)] rounded-2xl border border-white/10 bg-[#0a0f16] p-6 text-left">
        <div class="text-[0.7rem] uppercase tracking-[0.2em] text-white/50">Minha conta</div>
        <div class="mt-2 text-lg font-semibold text-white">{{ $authUserName }}</div>
        <div class="mt-5 space-y-3">
            <a class="block w-full rounded-full bg-emerald-400 px-4 py-2 text-center text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-[#081018] transition hover:brightness-110" href="{{ route('dashboard') }}">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full rounded-full border border-red-400/40 bg-red-500/10 px-4 py-2 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-red-200 transition hover:border-red-400/70 hover:text-white" type="submit">Logout</button>
            </form>
        </div>
        <button type="button" data-user-close class="mt-4 w-full rounded-full border border-white/20 bg-white/5 px-4 py-2 text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-white/70 transition hover:text-white">Fechar</button>
    </div>
</div>
