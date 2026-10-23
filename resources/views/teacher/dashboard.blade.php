@extends('layouts.admin')

@section('title', 'Espace enseignant | BestSchool')

@section('content')
<div class="admin-content" id="overview">
    <header class="admin-topbar">
        <div>
            <p class="admin-eyebrow">ESPACE ENSEIGNANT</p>
            <h1>Mes classes et évaluations</h1>
            <p class="admin-subtitle">Consultez vos affectations et saisissez les notes de vos élèves.</p>
        </div>
        <div class="admin-date">
            <x-heroicon-o-calendar-days />
            <span>{{ now()->translatedFormat('l d F Y') }}</span>
        </div>
    </header>

    @if (session('status'))
        <div class="admin-alert admin-alert-success" role="status">{{ session('status') }}</div>
    @endif

    <section class="admin-welcome">
        <div>
            <p class="admin-welcome-kicker">ESPACE SCOLAIRE AL-BOURHANE</p>
            <h2>Bonjour, {{ auth()->user()->prenom }} {{ auth()->user()->name }}</h2>
            <p>Les élèves et matières accessibles ici correspondent aux classes qui vous ont été affectées.</p>
        </div>
        <div class="admin-welcome-icon"><x-heroicon-o-academic-cap /></div>
    </section>

    <section class="admin-panel" id="classes">
        <div class="admin-panel-heading">
            <div>
                <p class="admin-eyebrow">MES AFFECTATIONS</p>
                <h2>Classes et matières</h2>
            </div>
            <span class="admin-panel-count">{{ $assignments->count() }} affectations</span>
        </div>
        @forelse ($assignments as $assignment)
            <div class="teacher-assignment">
                <span class="admin-list-icon stat-purple"><x-heroicon-o-rectangle-stack /></span>
                <span class="admin-list-copy">
                    <strong>{{ $assignment->classe_nom }} · {{ $assignment->matiere_nom }}</strong>
                    <small>{{ $assignment->eleves_count }} élèves dans cette classe</small>
                </span>
                <a class="admin-button" href="{{ route('teacher.evaluations.create', ['affectation' => $assignment->id]) }}">
                    <x-heroicon-o-plus />
                    Saisir une évaluation
                </a>
            </div>
        @empty
            <p class="admin-empty">Aucune classe ne vous a encore été affectée. Contactez l’administration de l’établissement.</p>
        @endforelse
    </section>

    <section class="admin-panel" id="evaluations">
        <div class="admin-panel-heading">
            <div>
                <p class="admin-eyebrow">SUIVI PÉDAGOGIQUE</p>
                <h2>Évaluations récentes</h2>
            </div>
            <span class="admin-panel-count">{{ $recentEvaluations->count() }} affichées</span>
        </div>
        @forelse ($recentEvaluations as $evaluation)
            <div class="admin-list-row">
                <span class="admin-list-icon stat-orange"><x-heroicon-o-clipboard-document-check /></span>
                <span class="admin-list-copy">
                    <strong>{{ $evaluation->titre }} · {{ $evaluation->matiere_nom }}</strong>
                    <small>{{ $evaluation->classe_nom }} · {{ $evaluation->date_evaluation->format('d/m/Y') }} · {{ $evaluation->notes_count }} notes</small>
                </span>
                <span class="admin-grade">
                    {{ $evaluation->moyenne !== null ? number_format((float) $evaluation->moyenne, 2, ',', ' ') : '—' }}/{{ number_format((float) $evaluation->note_max, 0, ',', ' ') }}
                </span>
            </div>
        @empty
            <p class="admin-empty">Vous n'avez pas encore créé d'évaluation.</p>
        @endforelse
    </section>

    <footer class="admin-footer">BestSchool · Espace enseignant</footer>
</div>
@endsection
