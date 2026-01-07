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
        
        <p class="form-group">
            <label for="nom">Nom *</label>
            {{-- value="{{ old('nom') }}" permet de garder le texte si erreur --}}
            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
        </p>
        <p class="form-group">
            <label for="prenom">Prénom *</label>
            <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
        </p>

        <p class="form-group">
            <label for="ville">Ville de naissance*</label>
            <input type="text" id="naiss_ville" name="naiss_ville" value="{{ old('ville') }}" required>
        </p>

        <p class="form-group">
            <label for="paysnaissance">Pays de naissance *</label>
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
            <label>Date de naissance *</label>
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
                    <option value="" disabled selected>Mois *</option>
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
                    <option value="" disabled selected>Année *</option>
                    @for ($i = date('Y')-3; $i >= 1920; $i--)
                        <option value="{{ $i }}" {{ old('annee_naissance') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </p>
        </div>

<div class="form-group">
    <label for="courriel">
        Courriel *
        <span class="tooltip-container">
            <span class="info-icon">i</span>
            <span class="tooltip-box">
                Le courriel doit respecter le format général. Il vous servira d'identifiant de connexion pour vous connecter à votre compte.
            </span>
        </span>
        </label>
    <p class="hint-text">Voici votre identifiant FIFA</p>
    <input type="email" id="courriel" name="courriel" value="{{ old('courriel') }}" required>
</div>


        <div class="form-group">
            <label for="cp">Code Postal *</label>
            <input type="text" id="cp" name="cp" maxlength="5" value="{{ old('cp') }}" placeholder="Ex: 75001" required>
        </div>


        <div class="form-group">
            <label for="ville">Ville *</label>
            

            <select id="ville_select" name="ville">
                <option value="">-- Remplissez le Code Postal d'abord --</option>
            </select>
            <input type="hidden" id="ville_real_name" name="ville_in" value="{{ old('ville') }}">
        </div>




        <p class="form-group">
            <label for="paysresidence">Pays de residence *</label>
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
            <label for="langue">Langue *</label>
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