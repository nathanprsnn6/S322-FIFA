@extends('layouts.app')

@section('content')
<section id="register-success" class="container">
    <p class="header-top">
        <a href="{{ url('/') }}">&larr; Retour à l'accueil</a>
    </p>

    <div style="text-align: center; margin-top: 40px;">
        <div style="margin-bottom: 20px; color: #28a745;">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>

        <h1>Inscription Réussie !</h1>
        <h2 style="margin-bottom: 30px;">Bienvenue parmi nous</h2>

        <div style="background-color: #d1ecf1; color: #0c5460; padding: 25px; border-radius: 5px; margin: 0 auto 40px auto; max-width: 600px; border: 1px solid #bee5eb;">
            <h3 style="margin-top: 0; font-size: 1.2em;">Vérifiez votre boîte mail</h3>
            <p>
                Votre compte a été créé avec succès. Un lien de vérification vient de vous être envoyé par email.<br>
                <strong>Merci de cliquer dessus pour activer pleinement votre compte.</strong>
            </p>
        </div>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 30px auto; max-width: 600px;">

        <div class="actions" style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
            
            <a href="{{ url('/') }}" 
               class="button" 
               style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Fermer
            </a>

            <a href="{{ route('pro.create') }}"
               class="button-primary" 
               style="padding: 10px 20px; text-decoration: none;">
                Devenir Compte Professionnel &rarr;
            </a>
        </div>
    </div>
</section>
@endsection