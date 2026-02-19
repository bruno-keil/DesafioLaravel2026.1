<section>
    <header>
        <h2 class="text-xl font-semibold text-white">
            {{ __('Informações do Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-white/60">
            {{ __("Atualize as informações do seu perfil e endereço de e-mail.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="nome" class="form-label-dark">{{ __('Nome') }}</label>
            <input id="nome" name="nome" type="text" class="form-input-dark placeholder-white/20" value="{{ old('nome', $user->nome) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
        </div>

        <div>
            <label for="email" class="form-label-dark">{{ __('E-mail') }}</label>
            <input id="email" name="email" type="email" class="form-input-dark placeholder-white/20" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-white/80">
                        {{ __('Seu endereço de e-mail não foi verificado.') }}

                        <button form="send-verification" class="underline text-sm text-emerald-400 hover:text-emerald-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            {{ __('Um novo link de verificação foi enviado para seu endereço de e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">
                {{ __('Salvar') }}
            </button>

            @if (session('status') === 'profile-updated')
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