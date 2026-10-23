<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Connexion | BestSchool')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-body">
    @include('layouts.partials.public-navigation')

    <main class="auth-main">
        <section class="auth-showcase" aria-label="Bienvenue à BestSchool">
            <div class="auth-showcase-content">
                <span class="auth-showcase-mark"><x-heroicon-o-academic-cap /></span>
                <p class="auth-kicker">COLLÈGE AL-BOURHANE</p>
                <h1>Grandir avec <span>confiance.</span></h1>
                <p class="auth-showcase-description">
                    Un espace pensé pour accompagner chaque élève et faciliter le quotidien de notre équipe éducative.
                </p>
                <div class="auth-showcase-note">
                    <x-heroicon-o-sparkles />
                    <span>Excellence académique, écoute et accompagnement.</span>
                </div>
            </div>
            <div class="auth-showcase-decoration auth-decoration-one"></div>
            <div class="auth-showcase-decoration auth-decoration-two"></div>
            <div class="auth-showcase-orbit"></div>
        </section>

        <section class="auth-form-section">
            <div class="auth-card">
                {{ $slot }}
            </div>
            <p class="auth-footer">© {{ now()->year }} BestSchool · Collège Al-Bourhane</p>
        </section>
    </main>
</body>

</html>
