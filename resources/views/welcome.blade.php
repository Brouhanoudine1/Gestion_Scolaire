@extends('layouts.app')

@section('title', 'BestChool')

@section('content')
<div class="accueil">
    <div class="accueil-step-one">
        <div class="parent-barre">
            <div class="barre">

            </div>
            <div class="barre-title">
                <p class="">Collège Al-Bourhane</p>
            </div>
        </div>
        <h2 class="target">APPRENDRE AVEC <br> <span>ENVIE.</span>
            GRANDIR <br>AVEC <span>CONFIANCE</span></h2>
        <p class="acceuil-texte">
            À Al-Bourhane School, chaque élève trouve sa voie grâce à une pédagogie <br> exigeante,
            bienveillante et résolument tournée vers l'avenir.
        </p>
        <div class="link">
            <div class="link-one">
                <a href="">
                    Découvrir notre école
                    <x-heroicon-o-chevron-right />
                </a>
            </div>
            <div class="link-two">
                <a href="">
                    Notre projet pédagogique
                    <x-heroicon-o-chevron-down />
                </a>
            </div>
        </div>
        <div class="parent-circle">
            <div class="circles">
                <div class="circle-one">
                    CL
                </div>
                <div class="circle-two">
                    AP
                </div>
                <div class="circle-three">
                    BR
                </div>
                <div class="circle-four">+10</div>
            </div>
            <div class="points">
                <p class="">4.5/5</p>
                <p class="">La satisfaction de nos familles</p>
            </div>
        </div>
    </div>
    <div class="accueil-step-two">
        <img src="{{ asset('images/student.jpg') }}" alt="Aperçu de nos élèves">
    </div>
</div>
<div class="statistiques">
    <div class="first-state">
        <h3>+500</h3>
        <p class="">Élèves épanouis</p>
    </div>
    <div class="second-state">
        <h3>+38</h3>
        <p class="">Enseignants passionnés</p>
    </div>
    <div class="third-state">
        <h3>+40</h3>
        <p class="">Élèves par classe</p>
    </div>
    <div class="fourth-state">
        <h3>+10</h3>
        <p class="">Années d'expériences</p>
    </div>
</div>
<div class="container" id="container">
    <div class="container-barre-title">
        <div class="bar"></div>
        <p class="">Notre différence</p>
    </div>
    <h2 class="container-title">
        Une école pensée pour révéler les <span class="container-span">potentiels.</span>
    </h2>
    <p class="container-texte">
        Nous conjuguons excellence académique, accompagnement personnalisé et ouverture sur le monde.
    </p>
    <div class="container-card">
        <div class="card-one">
            <div class="card-icone">
                <div class="space-one">
                    <x-heroicon-o-inbox />
                </div>
                <p class="number">
                    01
                </p>
            </div>
            <h3 class="card-title">Apprendre autrement</h3>
            <p class="">Des pédagogies actives et des projets concrets pour donner du sens à chaque apprentissage</p>
        </div>
        <div class="card-two">
            <div class="card-icone">
                <div class="space-two">
                    <x-heroicon-o-users />
                </div>
                <p class="number">
                    02
                </p>
            </div>
            <h3 class="card-title">Grandir ensemble</h3>
            <p class="">Des classes à taille humaine où chaque élève est connu, écouté et accompagné au quotidien.</p>
        </div>
        <div class="card-three">
            <div class="card-icone">
                <div class="space-three">
                    <x-heroicon-o-arrow-trending-up />
                </div>
                <p class="number">
                    03
                </p>
            </div>
            <h3 class="card-title">Ouvrir les horizons</h3>
            <p class="">Langues, numérique, arts et sciences pour préparer les citoyens curieux de demain.</p>
        </div>
    </div>
</div>
<div class="experience" id="experience">
    <div class="image">
        <img src="{{ asset('images/teacher.jpg')}}" alt="">
        <div class="image-texte">
            « Ici, on apprend à réussir, mais aussi à devenir soi. »
        </div>
    </div>
    <div class="details">
        <div class="experience-barre-title">
            <div class="experience-bar"></div>
            <p class="">L' expérience Al Bourhane</p>
        </div>
        <h2 class="experience-title">
            Bien plus qu'une journée <span>d'école.</span>
        </h2>
        <p class="experience-description">
            Du premier accueil le matin aux ateliers de fin de journée, chaque moment est pensé pour nourrir la curiosité et la confiance.
        </p>
        <div class="quality">
            <div class="quality-one">
                <div class="icone-place">
                    <x-heroicon-o-check />
                </div>
                <div class="icone-texte">
                    <h4>Suivi personnalisé</h4>
                    <p>Un tuteur référent pour chaque élève et un lien continu avec les familles.</p>
                </div>
            </div>
            <div class="quality-one">
                <div class="icone-place">
                    <x-heroicon-o-check />
                </div>
                <div class="icone-texte">
                    <h4>Campus vivant</h4>
                    <p>Plus de 20 clubs, ateliers et activités sportives chaque semaine.</p>
                </div>
            </div>
            <div class="quality-one">
                <div class="icone-place">
                    <x-heroicon-o-check />
                </div>
                <div class="icone-texte">
                    <h4>Ouverture internationale</h4>
                    <p>Parcours bilingue et échanges européens dès la 6ème.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="visite" id="visite">
    <div class="visite-items">
        <div class="visite-bar"></div>
        <p class="">RENTREE 2026-2027</p>
    </div>

    <div class="visites">
        <div class="visite-colonne">

            <h5>Et si l'aventure<br>commençait ici ?</h5>
            <p class="">Venez rencontrer notre équipe et découvrir le campus lors d'une visite personnalisée.</p>
        </div>
        <div class="visite-link">
            <a href="">
                Demander une visite
                <x-heroicon-o-chevron-right />
            </a>
        </div>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('css/welcome.css') }}?v={{ filemtime(public_path('css/welcome.css')) }}">
@endpush