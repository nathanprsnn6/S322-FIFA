@extends('layouts.app')

@section('content')
<div class="cookie-policy-container">
    <h1>Politique relative aux cookies</h1>
    <p><em>Dernière mise à jour : 16 janvier 2026</em></p>

    <section>
        <h2>1. Qu'est-ce qu'un cookie ?</h2>
        <p>
            Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, tablette ou mobile) lors de la visite d'un site. 
            Il permet de mémoriser vos préférences et d'optimiser votre navigation.
        </p>
    </section>

    <section id="manage-consent">
        <h2>2. Comment gérer vos préférences ?</h2>
        <p>Vous pouvez modifier vos choix à tout moment en cliquant sur le bouton ci-dessous :</p>
        
        <button onclick="tarteaucitron.userInterface.openPanel();" class="btn-manage">
            Gérer mes préférences de cookies
        </button>
    </section><br>

    <section>
        <h2>3. Quels cookies utilisons-nous ?</h2>
        
        <h3>1. Cookies Techniques (Obligatoires)</h3>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Hébergeur</th>
                        <th>Finalité</th>
                        <th>Durée</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>XSRF-TOKEN</code></td>
                        <td>Fifa</td>
                        <td>Sécurité : Protection contre les attaques CSRF.</td>
                        <td>Session</td>
                    </tr>
                    <tr>
                        <td><code>laravel_session</code></td>
                        <td>Fifa</td>
                        <td>Identification technique de votre session utilisateur.</td>
                        <td>2 heures</td>
                    </tr>
                    <tr>
                        <td><code>tarteaucitron</code></td>
                        <td>Fifa</td>
                        <td>Mémorisation de vos choix de consentement.</td>
                        <td>12 mois</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3>2. Cookies tiers (Soumis à consentement)</h3>
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Cookies typiques</th>
                        <th>Finalité</th>
                        <th>Durée max.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Google Services</strong></td>
                        <td><code>_gcl_au</code>, <code>NID</code>, <code>SID</code></td>
                        <td>Mesure d'audience et personnalisation des annonces.</td>
                        <td>6 à 24 mois</td>
                    </tr>
                    <tr>
                        <td><strong>Facebook</strong></td>
                        <td><code>datr</code>, <code>sb</code></td>
                        <td>Sécurité et ciblage publicitaire.</td>
                        <td>2 ans</td>
                    </tr>
                </tbody>
            </table>            
        </div>
    </section>
</div>
@endsection