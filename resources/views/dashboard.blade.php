@extends('layouts.admin')

@section('title', 'Gestion scolaire | BestSchool')

@section('content')
<div class="admin-content" id="overview">
    <header class="admin-topbar">
        <div>
            <p class="admin-eyebrow">ESPACE ADMINISTRATEUR</p>
            <h1>Gestion scolaire</h1>
            <p class="admin-subtitle">Inscriptions, classes, matières et affectations des enseignants.</p>
        </div>
        <div class="admin-date">
            <x-heroicon-o-calendar-days />
            <span>{{ now()->translatedFormat('l d F Y') }}</span>
        </div>
    </header>

    @if (session('status'))
        <div class="admin-alert admin-alert-success" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="admin-alert admin-alert-error" role="alert">
            <strong>Vérifiez les informations saisies :</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="admin-workflow-panel" id="saisie-notes">
        <span class="admin-workflow-icon"><x-heroicon-o-clipboard-document-check /></span>
        <div class="admin-workflow-copy">
            <p class="admin-eyebrow">SAISIE DES NOTES PAR LES ENSEIGNANTS</p>
            <h2>La saisie se fait depuis le compte de chaque enseignant</h2>
            <p>
                Créez son compte, préparez les classes et les matières, puis effectuez ses affectations.
                <strong>La saisie des notes n’est pas disponible dans la session administrateur.</strong>
                L’enseignant doit se déconnecter de ce compte et ouvrir sa propre session pour retrouver ses classes et le bouton
                <strong>« Saisir une évaluation »</strong> pour noter les élèves sur 20.
            </p>
            <div class="admin-workflow-links">
                <a href="#enseignants">1. Créer un enseignant</a>
                <a href="#classes">2. Préparer classe et matière</a>
                <a href="#affectations">3. Faire les affectations</a>
            </div>
            @if ($teacherAssignments->isNotEmpty())
                <div class="admin-teacher-access">
                    <strong>Comptes enseignants avec classes affectées</strong>
                    @foreach ($teacherAssignments as $teacherAssignment)
                        <div class="admin-teacher-access-row">
                            <span>
                                <b>{{ $teacherAssignment->prenom }} {{ $teacherAssignment->name }}</b>
                                <small>{{ $teacherAssignment->email }}</small>
                            </span>
                            <span>
                                <b>{{ $teacherAssignment->classe_nom }} · {{ $teacherAssignment->matiere_nom }}</b>
                                <small>{{ $teacherAssignment->eleves_count }} élèves</small>
                            </span>
                        </div>
                    @endforeach
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="admin-button" type="submit">Déconnexion et accès à l’espace enseignant</button>
                    </form>
                    <p class="admin-teacher-access-hint">Après déconnexion, ouvrez /login et utilisez l'adresse e-mail ainsi que le mot de passe de ce compte enseignant.</p>
                </div>
            @else
                <p class="admin-teacher-access-hint">Aucune affectation n’est prête : créez un compte enseignant, une classe et une matière, puis reliez-les dans « Affectations ».</p>
            @endif
        </div>
    </section>

    <section class="admin-welcome">
        <div>
            <p class="admin-welcome-kicker">ESPACE SCOLAIRE AL-BOURHANE</p>
            <h2>Bonjour, {{ auth()->user()->prenom }} {{ auth()->user()->name }}</h2>
            <p>Préparez les classes et les affectations pour permettre aux enseignants de saisir leurs évaluations.</p>
        </div>
        <div class="admin-welcome-icon"><x-heroicon-o-academic-cap /></div>
    </section>

    <section class="admin-stat-grid" aria-label="Effectifs scolaires">
        <a class="admin-stat-card" href="#eleves">
            <span class="admin-stat-icon stat-purple"><x-heroicon-o-users /></span>
            <span class="admin-stat-label">Élèves</span>
            <strong>{{ number_format($studentCount, 0, ',', ' ') }}</strong>
            <span class="admin-stat-foot">Inscrits dans l'établissement</span>
        </a>
        <a class="admin-stat-card" href="#classes">
            <span class="admin-stat-icon stat-blue"><x-heroicon-o-rectangle-stack /></span>
            <span class="admin-stat-label">Classes</span>
            <strong>{{ number_format($classCount, 0, ',', ' ') }}</strong>
            <span class="admin-stat-foot">Groupes d'élèves</span>
        </a>
        <a class="admin-stat-card" href="#enseignants">
            <span class="admin-stat-icon stat-green"><x-heroicon-o-academic-cap /></span>
            <span class="admin-stat-label">Enseignants</span>
            <strong>{{ number_format($teachers->count(), 0, ',', ' ') }}</strong>
            <span class="admin-stat-foot">Comptes enseignants</span>
        </a>
        <a class="admin-stat-card" href="#affectations">
            <span class="admin-stat-icon stat-orange"><x-heroicon-o-book-open /></span>
            <span class="admin-stat-label">Matières</span>
            <strong>{{ number_format($subjectCount, 0, ',', ' ') }}</strong>
            <span class="admin-stat-foot">Enseignements disponibles</span>
        </a>
    </section>

    <section class="admin-panel" id="eleves">
        <div class="admin-panel-heading">
            <div>
                <p class="admin-eyebrow">INSCRIPTIONS</p>
                <h2>Inscrire un élève</h2>
            </div>
        </div>
        @if ($classes->isEmpty())
            <p class="admin-empty">Créez d'abord une classe avant d'inscrire un élève.</p>
        @else
            <form class="admin-form-grid" method="POST" action="{{ route('admin.students.store') }}">
                @csrf
                <label class="admin-field">
                    <span>Prénom</span>
                    <input name="prenom" value="{{ old('prenom') }}" autocomplete="given-name" required>
                </label>
                <label class="admin-field">
                    <span>Nom</span>
                    <input name="nom" value="{{ old('nom') }}" autocomplete="family-name" required>
                </label>
                <label class="admin-field">
                    <span>Classe</span>
                    <select name="id_class" required>
                        <option value="">Choisir une classe</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('id_class') == $class->id)>
                                {{ $class->nom }}{{ $class->libelle ? ' — '.$class->libelle : '' }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <button class="admin-button" type="submit">
                    <x-heroicon-o-user-plus />
                    Inscrire l'élève
                </button>
            </form>
        @endif
    </section>

    <section class="admin-panel" id="enseignants">
        <div class="admin-panel-heading">
            <div>
                <p class="admin-eyebrow">ÉQUIPE PÉDAGOGIQUE</p>
                <h2>Créer un compte enseignant</h2>
            </div>
        </div>
        <form class="admin-form-grid" method="POST" action="{{ route('admin.teachers.store') }}">
            @csrf
            <label class="admin-field">
                <span>Prénom</span>
                <input name="prenom" value="{{ old('prenom') }}" autocomplete="given-name" required>
            </label>
            <label class="admin-field">
                <span>Nom</span>
                <input name="name" value="{{ old('name') }}" autocomplete="family-name" required>
            </label>
            <label class="admin-field">
                <span>Téléphone</span>
                <input name="telephone" value="{{ old('telephone') }}" autocomplete="tel" required>
            </label>
            <label class="admin-field">
                <span>Adresse e-mail</span>
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
            </label>
            <label class="admin-field">
                <span>Mot de passe initial</span>
                <input type="password" name="password" autocomplete="new-password" required>
            </label>
            <label class="admin-field">
                <span>Confirmer le mot de passe</span>
                <input type="password" name="password_confirmation" autocomplete="new-password" required>
            </label>
            <button class="admin-button" type="submit">
                <x-heroicon-o-user-plus />
                Créer le compte enseignant
            </button>
        </form>
        @if ($teachers->isNotEmpty())
            <div class="admin-inline-list">
                <strong>Enseignants enregistrés</strong>
                <div class="admin-chip-list">
                    @foreach ($teachers as $teacher)
                        <span class="admin-chip">{{ $teacher->prenom }} {{ $teacher->name }}</span>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <div class="admin-section-grid">
        <section class="admin-panel" id="classes">
            <div class="admin-panel-heading">
                <div>
                    <p class="admin-eyebrow">ORGANISATION</p>
                    <h2>Créer une classe</h2>
                </div>
                <span class="admin-panel-count">{{ $classCount }} au total</span>
            </div>
            <form class="admin-form-stack" method="POST" action="{{ route('admin.classes.store') }}">
                @csrf
                <label class="admin-field">
                    <span>Nom de la classe</span>
                    <input name="nom" value="{{ old('nom') }}" placeholder="Ex. 6e A" required>
                </label>
                <label class="admin-field">
                    <span>Niveau ou précision (facultatif)</span>
                    <input name="libelle" value="{{ old('libelle') }}" placeholder="Ex. Sixième">
                </label>
                <button class="admin-button" type="submit">Ajouter la classe</button>
            </form>
            <div class="admin-chip-list admin-chip-list-spaced">
                @forelse ($classes as $class)
                    <span class="admin-chip">{{ $class->nom }} <small>{{ $class->eleves_count }} élèves</small></span>
                @empty
                    <p class="admin-empty">Aucune classe enregistrée.</p>
                @endforelse
            </div>
        </section>

        <section class="admin-panel" id="matieres">
            <div class="admin-panel-heading">
                <div>
                    <p class="admin-eyebrow">ENSEIGNEMENTS</p>
                    <h2>Créer une matière</h2>
                </div>
                <span class="admin-panel-count">{{ $subjectCount }} au total</span>
            </div>
            <form class="admin-form-stack" method="POST" action="{{ route('admin.subjects.store') }}">
                @csrf
                <label class="admin-field">
                    <span>Nom de la matière</span>
                    <input name="nom" value="{{ old('nom') }}" placeholder="Ex. Mathématiques" required>
                </label>
                <label class="admin-field">
                    <span>Code</span>
                    <input name="code" value="{{ old('code') }}" placeholder="Ex. MATH" required>
                </label>
                <button class="admin-button" type="submit">Ajouter la matière</button>
            </form>
            <div class="admin-chip-list admin-chip-list-spaced">
                @forelse ($subjects as $subject)
                    <span class="admin-chip">{{ $subject->nom }} <small>{{ $subject->code }}</small></span>
                @empty
                    <p class="admin-empty">Aucune matière enregistrée.</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="admin-panel" id="affectations">
        <div class="admin-panel-heading">
            <div>
                <p class="admin-eyebrow">RÉPARTITION DES ENSEIGNEMENTS</p>
                <h2>Affecter un enseignant à une classe et une matière</h2>
            </div>
        </div>
        @if ($teachers->isEmpty() || $classes->isEmpty() || $subjects->isEmpty())
            <p class="admin-empty">Ajoutez au moins un enseignant, une classe et une matière pour créer une affectation.</p>
        @else
            <form class="admin-form-grid" method="POST" action="{{ route('admin.assignments.store') }}">
                @csrf
                <label class="admin-field">
                    <span>Enseignant</span>
                    <select name="id_user" required>
                        <option value="">Choisir un enseignant</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('id_user') == $teacher->id)>
                                {{ $teacher->prenom }} {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="admin-field">
                    <span>Classe</span>
                    <select name="id_classe" required>
                        <option value="">Choisir une classe</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('id_classe') == $class->id)>{{ $class->nom }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="admin-field">
                    <span>Matière</span>
                    <select name="id_matiere" required>
                        <option value="">Choisir une matière</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('id_matiere') == $subject->id)>{{ $subject->nom }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="admin-button" type="submit">
                    <x-heroicon-o-link />
                    Enregistrer l'affectation
                </button>
            </form>
        @endif
    </section>

    <section class="admin-panel">
        <div class="admin-panel-heading">
            <div>
                <p class="admin-eyebrow">DERNIÈRES INSCRIPTIONS</p>
                <h2>Élèves récemment inscrits</h2>
            </div>
            <span class="admin-panel-count">{{ $studentCount }} élèves</span>
        </div>
        @if ($recentStudents->isEmpty())
            <p class="admin-empty">Aucun élève n'est encore enregistré.</p>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Date d'inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentStudents as $student)
                            <tr>
                                <td>
                                    <span class="student-cell">
                                        <span class="student-avatar">{{ mb_substr($student->prenom, 0, 1) }}{{ mb_substr($student->nom, 0, 1) }}</span>
                                        <strong>{{ $student->prenom }} {{ $student->nom }}</strong>
                                    </span>
                                </td>
                                <td>{{ $student->classe_nom ?? '—' }}</td>
                                <td>{{ $student->created_at?->format('d/m/Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <footer class="admin-footer">BestSchool · Gestion de la vie scolaire</footer>
</div>
@endsection
