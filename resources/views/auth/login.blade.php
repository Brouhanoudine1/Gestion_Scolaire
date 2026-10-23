<x-guest-layout>
    <div class="auth-heading">
        <p class="auth-kicker">HEUREUX DE VOUS REVOIR</p>
        <h2>Connexion</h2>
        <p>Accédez à votre espace scolaire sécurisé.</p>
    </div>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form class="auth-form" method="POST" action="{{ route('login') }}">
        @csrf

        <label class="auth-field" for="email">
            <span>Adresse e-mail</span>
            <span class="auth-input-wrap">
                <x-heroicon-o-envelope />
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nom@exemple.com">
            </span>
            <x-input-error :messages="$errors->get('email')" class="auth-error" />
        </label>

        <label class="auth-field" for="password">
            <span>Mot de passe</span>
            <span class="auth-input-wrap">
                <x-heroicon-o-lock-closed />
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Votre mot de passe">
            </span>
            <x-input-error :messages="$errors->get('password')" class="auth-error" />
        </label>

        <div class="auth-options">
            <label class="auth-remember" for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Se souvenir de moi</span>
            </label>
            @if (Route::has('password.request'))
                <a class="auth-forgot" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <button class="auth-submit" type="submit">
            Se connecter
            <x-heroicon-o-arrow-right />
        </button>
    </form>

    <div class="auth-help">
        <span class="auth-help-icon"><x-heroicon-o-shield-check /></span>
        <p>Votre espace est réservé aux membres autorisés de l’établissement.</p>
    </div>
</x-guest-layout>
