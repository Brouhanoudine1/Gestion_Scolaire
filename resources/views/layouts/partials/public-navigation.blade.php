<header class="site-header">
    <nav class="navbar">
        <a class="navbars" href="{{ url('/') }}" aria-label="BestSchool - Accueil">
            <span class="academic-cap">
                <x-heroicon-o-academic-cap />
            </span>
            <span class="navbar-title">BestSchool</span>
        </a>

        <ul class="navbar-links">
            <li class="navbar-home">
                <x-heroicon-o-home />
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Accueil</a>
            </li>
            <li class="navbar-pedagogie">
                <x-heroicon-o-briefcase />
                <a href="{{ url('/welcome#container') }}" class="{{ request()->is('welcome') ? 'active' : '' }}">Pédagogie</a>
            </li>
            <li class="navbar-life">
                <x-heroicon-o-user-group />
                <a href="{{ url('/welcome#experience') }}">Scolaire</a>
            </li>
            <li class="navbar-admission">
                <x-heroicon-o-megaphone />
                <a href="{{ url('/welcome#visite') }}">Admissions</a>
            </li>
        </ul>

        <a class="connexion-space" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
            <x-heroicon-o-users />
            <span>Espace admin</span>
        </a>
    </nav>
</header>
