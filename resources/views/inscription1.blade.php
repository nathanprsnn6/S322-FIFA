@extends('layouts.app')

@section('content')
<section id="p1" class="container">
    <div class="dots">
        <div class="dotN"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>
    <p class="header-top">
        Vous avez déjà un compte ? <a href="connexion">&nbsp; Se connecter</a>
    </p>
    <p class="header-top">
        Les champs marqués d'un &nbsp;*&nbsp; sont obligatoires<br><br>
    </p>

    {{-- AFFICHER LES ERREURS (Pour comprendre pourquoi ça bloque) --}}
    @if ($errors->any())
        <div style="color: red; background: #ffcccc; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h1>S'inscrire</h1>
    <h2>Étape 1 sur 3<br><b>Données personnelles</b></h2>

    {{-- AJOUT DE L'ACTION ET DU TOKEN CSRF --}}
    <form action="{{ route('inscription1.store') }}" method="POST">
        @csrf {{-- OBLIGATOIRE pour que Laravel accepte le formulaire --}}


        <a href="{{ route('google.login') }}" class="btn-google-integrated">
            <svg class="google-svg" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
            </svg>
            <span>Continuer avec Google</span>
        </a>

                        <div class="separator">
            <span>OU</span>
        </div>
        <p class="form-group">
            <label for="nom">Nom <span class="etoile">*</span></label>
            {{-- value="{{ old('nom') }}" permet de garder le texte si erreur --}}
            <input type="text" id="nom" name="nom" value="{{ session('google_data.nom') ?? old('nom') }}" required placeholder="Ex: Dupont">
        </p>
        <p class="form-group">
            <label for="prenom">Prénom <span class="etoile">*</span></label>
            <input type="text" id="prenom" name="prenom" value="{{ session('google_data.prenom') ?? old('prenom') }}" required placeholder="Ex: François">
        </p>

        <p class="form-group">
            <label for="ville">Ville de naissance <span class="etoile">*</span></label>
            <input type="text" id="naiss_ville" name="naiss_ville" value="{{ old('ville') }}" required placeholder="Ex: Paris">
        </p>

        <p class="form-group">
            <label for="paysnaissance">Pays de naissance <span class="etoile">*</span></label>
            <select id="paysnaissance" name="pays_naissance" class="form-control" required>
                <option value="">-- Choisir un pays de naissance --</option>
                @foreach($nations as $nation)
                    {{-- On force l'utilisation de idnation --}}
                    <option value="{{ $nation->idnation }}" {{ old('pays_naissance') == $nation->idnation ? 'selected' : '' }}>
                        {{ $nation->nomnation }}
                    </option>
                @endforeach
            </select>
        </p>

        <div class="form-group">
        <label>
        Date de naissance <span class="etoile">*</span>
        <span class="tooltip-container" role="button" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-label="Aide sur la date de naissance">
            <span class="info-icon" aria-hidden="true">i</span>
            <span class="tooltip-box" id="desc-naissance" role="tooltip">
                Vous devez être âgé d'au moins 16 ans pour créer un compte personnel.
            </span>
        </span>
    </label>
            <p class="date-selects">
                <select id="jour_naissance" name="jour_naissance" required>
                    <option value="" disabled selected>Jour</option>
                    @for ($i = 1; $i <= 31; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ old('jour_naissance') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>

                <select id="mois_naissance" name="mois_naissance" required>
                    <option value="" disabled selected>Mois</option>
                    @php
                        $mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
                                 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                    @endphp
                    @foreach ($mois as $index => $nomMois)
                        @php $valMois = str_pad($index + 1, 2, '0', STR_PAD_LEFT); @endphp
                        <option value="{{ $valMois }}" {{ old('mois_naissance') == $valMois ? 'selected' : '' }}>
                            {{ $nomMois }}
                        </option>
                    @endforeach
                </select>

                <select id="annee_naissance" name="annee_naissance" required>
                    <option value="" disabled selected>Année</option>
                    @for ($i = date('Y')-3; $i >= 1920; $i--)
                        <option value="{{ $i }}" {{ old('annee_naissance') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </p>
        </div>

        <div class="form-group">
            <label for="courriel">
                Courriel <span class="etoile">*</span>
                <span class="tooltip-container" 
                    role="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                    tabindex="0" 
                    aria-label="Plus d'informations sur le courriel">
                    
                    <span class="info-icon" aria-hidden="true">i</span>
                    
                    <span class="tooltip-box" 
                        id="desc-courriel" 
                        role="tooltip">
                        Le courriel doit respecter le format général. Il vous servira d'identifiant de connexion pour vous connecter à votre compte.
                    </span>
                </span>
            </label>
            <p class="hint-text">Voici votre identifiant FIFA</p>
            <input type="email" id="courriel" name="courriel" 
                aria-describedby="desc-courriel" 
                value="{{ session('google_data.courriel') ?? old('courriel') }}" required placeholder="Ex: dupont.françois@gmail.com">
        </div>


        <div class="form-group">
        <label for="cp">
        Code Postal <span class="etoile">*</span>
        <span class="tooltip-container" role="button" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-label="Aide sur le code postal">
            <span class="info-icon" aria-hidden="true">i</span>
            <span class="tooltip-box" id="desc-cp" role="tooltip">
                Saisissez les 5 chiffres de votre code postal pour charger automatiquement la liste des villes.
            </span>
        </span>
    </label>
            <input type="text" id="cp" name="cp" maxlength="5" value="{{ old('cp') }}" placeholder="Ex: 75001" required>
        </div>


        <div class="form-group">
            <label for="ville">Ville <span class="etoile">*</span></label>
            

            <select id="ville_select" name="ville">
                <option value="">-- Remplissez le Code Postal d'abord --</option>
            </select>
            <input type="hidden" id="ville_real_name" name="ville_in" value="{{ old('ville') }}">
        </div>




        <p class="form-group">
        <label for="paysresidence">
        Pays de résidence <span class="etoile">*</span></label>
            <select id="paysresidence" name="pays_residence" class="form-control" required>
                <option value="">-- Choisir un pays de residence --</option>
                @foreach($nations as $nation)
                    <option value="{{ $nation->idnation ?? $nation->id }}" {{ old('pays_residence') == ($nation->idnation ?? $nation->id) ? 'selected' : '' }}>
                        {{ $nation->nomnation }}
                    </option>
                @endforeach
            </select>
        </p>




        <p class="form-group">
            <label for="langue">Langue <span class="etoile">*</span></label>
            <select id="langue" name="langue" class="form-control" required>
                <option value="">-- Choisir une langue --</option>
                @foreach($nations as $nation)
                <option value="{{ $nation->idnation }}" {{ old('langue') == $nation->idnation ? 'selected' : '' }}>
                    {{ $nation->nomnation }}
                </option>
                @endforeach
            </select>
        </p>

        <button type="submit" class="button-primary btnP2" id="submitButton1">POURSUIVRE</button>
    </form>
</section>
@endsection 

@section('scripts')
<script src="{{ asset('js/main.js') }}" defer></script>
@endsection