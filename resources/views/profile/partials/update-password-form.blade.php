<section>
    <header>
        <h2 class="text-xl font-semibold text-white">
            {{ __('Atualizar Senha') }}
        </h2>

        <p class="mt-1 text-sm text-white/60">
            {{ __('Certifique-se de que sua conta esteja usando uma senha longa e aleatória para se manter segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label-dark">{{ __('Senha Atual') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-input-dark placeholder-white/20" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="form-label-dark">{{ __('Nova Senha') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-input-dark placeholder-white/20" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-label-dark">{{ __('Confirmar Nova Senha') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input-dark placeholder-white/20" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">
                {{ __('Salvar') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400"
                >{{ __('Salvo com sucesso.') }}</p>
            @endif
        </div>
    </form>
</section>