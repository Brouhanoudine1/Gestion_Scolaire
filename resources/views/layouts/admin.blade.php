<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration | BestSchool')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>

<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="admin-brand" href="{{ route('dashboard') }}">
                <span class="admin-brand-icon"><x-heroicon-o-academic-cap /></span>
                <span>
                    <strong>BestSchool</strong>
                    <small>Espace de gestion</small>
                </span>
            </a>

            <p class="admin-nav-label">MENU PRINCIPAL</p>
            <nav class="admin-nav" aria-label="Navigation d'administration">
                @if (auth()->user()->role === 'admin')
                    <a class="admin-nav-link" href="{{ route('admin.dashboard') }}">
                        <x-heroicon-o-squares-2x2 />
                        <span>Gestion scolaire</span>
                    </a>
                    <a class="admin-nav-link" href="{{ route('admin.dashboard') }}#eleves">
                        <x-heroicon-o-users />
                        <span>Élèves</span>
                    </a>
                    <a class="admin-nav-link" href="{{ route('admin.dashboard') }}#enseignants">
                        <x-heroicon-o-academic-cap />
                        <span>Enseignants</span>
                    </a>
                    <a class="admin-nav-link" href="{{ route('admin.dashboard') }}#classes">
                        <x-heroicon-o-rectangle-stack />
                        <span>Classes et matières</span>
                    </a>
                    <a class="admin-nav-link" href="{{ route('admin.dashboard') }}#affectations">
                        <x-heroicon-o-link />
                        <span>Affectations</span>
                    </a>
                    <a class="admin-nav-link" href="{{ route('admin.dashboard') }}#saisie-notes">
                        <x-heroicon-o-clipboard-document-check />
                        <span>Saisie des notes</span>
                    </a>
                @else
                    <a class="admin-nav-link" href="{{ route('teacher.dashboard') }}">
                        <x-heroicon-o-squares-2x2 />
                        <span>Mon espace</span>
                    </a>
                    <a class="admin-nav-link" href="{{ route('teacher.dashboard') }}#classes">
                        <x-heroicon-o-rectangle-stack />
                        <span>Mes classes</span>
                    </a>
                    <a class="admin-nav-link" href="{{ route('teacher.dashboard') }}#evaluations">
                        <x-heroicon-o-clipboard-document-check />
                        <span>Mes évaluations</span>
                    </a>
                @endif
            </nav>

            <div class="admin-sidebar-bottom">
                <a class="admin-nav-link" href="{{ url('/') }}">
                    <x-heroicon-o-home />
                    <span>Voir le site</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="admin-nav-link admin-logout" type="submit">
                        <x-heroicon-o-arrow-left-on-rectangle />
                        <span>Déconnexion</span>
                    </button>
                </form>
                <div class="admin-user">
                    <span class="admin-avatar">{{ mb_substr(auth()->user()->prenom, 0, 1) }}{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    <span class="admin-user-name">
                        <strong>{{ auth()->user()->prenom }} {{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->role === 'admin' ? 'Administrateur' : 'Enseignant' }}</small>
                    </span>
                </div>
            </div>
        </aside>

        <main class="admin-main">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
