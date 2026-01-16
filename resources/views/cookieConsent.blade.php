@extends('layouts.app')

@section('content')
    <div class="cookie-policy-container">
        <h1>Politique relative aux cookies</h1>
        <p>Dernière mise à jour : {{ $lastUpdated }}</p>

        <section>
            <h2>1. Qu'est-ce qu'un cookie ?</h2>
            <p>Explication simple sur les traceurs et leur utilité.</p>
        </section>

        <section id="manage-consent">
            <h2>2. Comment gérer vos préférences ?</h2>
            <p>Vous pouvez modifier vos choix à tout moment en cliquant sur le bouton ci-dessous :</p>
            
            {{-- Bouton pour réouvrir Tarteaucitron ou votre système de gestion --}}
            <button onclick="tarteaucitron.userInterface.openPanel();" class="btn-manage">
                Gérer mes préférences cookies
            </button>
        </section>

        <section>
            <h2>3. Quels cookies utilisons-nous ?</h2>
            
            <div class="cookie-category">
                <h3>Cookies strictement nécessaires (Obligatoires)</h3>
                <p>Ces cookies sont indispensables au fonctionnement du site (session, sécurité, panier).</p>
                @include('partials.cookie-table', ['type' => 'essentials'])
            </div>

            <div class="cookie-category">
                <h3>Cookies de mesure d'audience</h3>
                <p>Ils nous permettent de comprendre comment vous utilisez notre site.</p>
                @include('partials.cookie-table', ['type' => 'analytics'])
            </div>
        </section>

        <section>
            <h2>4. Durée de conservation et destinataires</h2>
            <p>Précisez ici que les données ne sont pas gardées plus de 13 ou 25 mois selon les cas.</p>
        </section>
    </div>
@endsection