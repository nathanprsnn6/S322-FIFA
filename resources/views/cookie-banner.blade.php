@extends('layouts.app')

@section('content')
<section>
    <div id="cookie-banner" style="position: fixed; bottom: 0; width: 100%; background-color: #333; color: white; padding: 15px; text-align: center; z-index: 1000;">
        <p style="margin-bottom: 10px;">
            Ce site utilise des cookies pour améliorer l'expérience utilisateur. 
            Consultez notre <a href="{{ route('cookie.policy') }}" style="color: lightblue;">politique de cookies</a>.
        </p>

        <button id="accept-all-cookies" style="background-color: green; color: white; border: none; padding: 8px 15px; cursor: pointer;">
            J'accepte tout
        </button>
        
        <button id="customize-cookies" style="background-color: orange; color: white; border: none; padding: 8px 15px; cursor: pointer;">
            Gérer mes préférences
        </button>
    </div>
</section>

{{-- Script JS pour gérer les clics et appeler la route POST /cookie-consent --}}
<script>
    document.getElementById('accept-all-cookies').addEventListener('click', function() {
        // Logique pour envoyer la requête AJAX à votre route POST /cookie-consent 
        // avec toutes les catégories définies sur 'true'.
        
        const consentData = {
            analytics: true,
            marketing: true,
            _token: document.querySelector('meta[name="csrf-token"]').content // Le jeton CSRF est important !
        };
        
        fetch("{{ route('cookie.consent.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(consentData)
        })
        .then(response => {
            if (response.ok) {
                // Masquer la bannière et recharger peut-être la page si nécessaire
                document.getElementById('cookie-banner').style.display = 'none';
                window.location.reload(); 
            }
        });
    });


</script>
@endsection