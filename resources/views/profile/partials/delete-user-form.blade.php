<section class="space-y-6">
    <header>
        <h2 class="text-xl font-semibold text-red-400">
            {{ __('Deletar Conta') }}
        </h2>

        <p class="mt-1 text-sm text-white/60">
            {{ __('Depois que sua conta for excluída, todos os seus recursos e dados serão excluídos permanentemente.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2 rounded-full border border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white transition"
    >{{ __('Deletar Conta') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-[#0a0f16] border border-white/10 text-white">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-white">
                {{ __('Tem certeza de que deseja deletar sua conta?') }}
            </h2>

            <p class="mt-1 text-sm text-white/60">
                {{ __('Por favor, digite sua senha para confirmar que você deseja excluir permanentemente sua conta.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">{{ __('Senha') }}</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-3/4 bg-black/20 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 placeholder-white/20"
                    placeholder="{{ __('Senha') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-full border border-white/20 text-white/70 hover:text-white hover:border-white/40 transition">
                    {{ __('Cancelar') }}
                </button>

                <button type="submit" class="px-4 py-2 rounded-full bg-red-500 hover:bg-red-600 text-white font-semibold transition">
                    {{ __('Deletar Conta') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>