@extends('layouts.admin')

@section('title', 'Saisie des notes | BestSchool')

@section('content')
<div class="admin-content">
    <header class="admin-topbar">
        <div>
            <p class="admin-eyebrow">SAISIE DES NOTES</p>
            <h1>{{ $assignment->classe_nom }} · {{ $assignment->matiere_nom }}</h1>
            <p class="admin-subtitle">Créez l'évaluation et saisissez une note sur 20 pour chaque élève.</p>
        </div>
        <a class="admin-back-link" href="{{ route('teacher.dashboard') }}">
            <x-heroicon-o-arrow-left />
            Retour à mes classes
        </a>
    </header>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error" role="alert">
            <strong>L'évaluation n'a pas pu être enregistrée :</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="admin-panel">
        <div class="admin-panel-heading">
            <div>
                <p class="admin-eyebrow">NOUVELLE ÉVALUATION</p>
                <h2>Informations de l'évaluation</h2>
            </div>
            <span class="admin-panel-count">{{ $students->count() }} élèves · /20</span>
        </div>

        @if ($students->isEmpty())
            <p class="admin-empty">Cette classe ne contient encore aucun élève. Contactez l'administration avant de saisir une évaluation.</p>
        @else
            <form method="POST" action="{{ route('teacher.evaluations.store') }}">
                @csrf
                <input type="hidden" name="id_affectation" value="{{ $assignment->id }}">

                <div class="admin-form-grid evaluation-meta">
                    <label class="admin-field">
                        <span>Intitulé de l'évaluation</span>
                        <input name="titre" value="{{ old('titre') }}" placeholder="Ex. Contrôle du chapitre 2" required maxlength="255">
                    </label>
                    <label class="admin-field">
                        <span>Date de l'évaluation</span>
                        <input type="date" name="date_evaluation" value="{{ old('date_evaluation', now()->toDateString()) }}" required>
                    </label>
                </div>

                <div class="admin-grade-list">
                    @foreach ($students as $student)
                        <label class="admin-grade-row" for="note-{{ $student->id }}">
                            <span class="student-cell">
                                <span class="student-avatar">{{ mb_substr($student->prenom, 0, 1) }}{{ mb_substr($student->nom, 0, 1) }}</span>
                                <strong>{{ $student->prenom }} {{ $student->nom }}</strong>
                            </span>
                            <span class="admin-score-input">
                                <input
                                    id="note-{{ $student->id }}"
                                    type="number"
                                    name="notes[{{ $student->id }}]"
                                    value="{{ old('notes.'.$student->id) }}"
                                    min="0"
                                    max="20"
                                    step="0.25"
                                    required
                                >
                                <span>/ 20</span>
                            </span>
                        </label>
                    @endforeach
                </div>

                <div class="admin-form-actions">
                    <a class="admin-cancel-link" href="{{ route('teacher.dashboard') }}">Annuler</a>
                    <button class="admin-button" type="submit">
                        <x-heroicon-o-check />
                        Enregistrer les notes
                    </button>
                </div>
            </form>
        @endif
    </section>
</div>
@endsection
