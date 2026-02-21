@props([
    'id' => 'delete-modal',
])

<div
    x-data="{ open: false, action: '', itemName: '' }"
    x-on:open-delete-modal.window="open = true; action = $event.detail.action; itemName = $event.detail.name"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
    style="display: none;"
>
    <div class="absolute inset-0" x-on:click="open = false"></div>

    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-[min(400px,90vw)] rounded-2xl border border-white/10 bg-[#0a0f16] p-6 text-center shadow-2xl"
    >
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-500/10 border border-red-400/30">
            <i class="bi bi-exclamation-triangle text-2xl text-red-400"></i>
        </div>

        <h3 class="mt-4 text-lg font-semibold text-white">Confirmar exclusão</h3>

        <p class="mt-2 text-sm text-white/60">
            Tem certeza que deseja excluir <span class="font-medium text-white" x-text="itemName"></span>?
            Esta ação não pode ser desfeita.
        </p>

        <div class="mt-6 flex items-center justify-center gap-3">
            <button
                type="button"
                x-on:click="open = false"
                class="rounded-full border border-white/20 bg-white/5 px-5 py-2 text-[0.75rem] font-semibold uppercase tracking-[0.15em] text-white/70 transition hover:text-white hover:border-white/40"
            >
                Cancelar
            </button>

            <form :action="action" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="rounded-full bg-red-500 px-5 py-2 text-[0.75rem] font-semibold uppercase tracking-[0.15em] text-white transition hover:bg-red-600"
                >
                    Excluir
                </button>
            </form>
        </div>
    </div>
</div>
