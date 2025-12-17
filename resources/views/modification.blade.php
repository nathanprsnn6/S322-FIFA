    @extends('layouts.app')

    @section('content')
    <section id="edit-profile" class="container">
        <p class="header-top">
            <a href="{{ url('/') }}">&larr; Retour à l'accueil</a>
        </p>

        @php
        // Si la date existe, on crée un objet Carbon, sinon null
            $date = $user->personne->datenaissance ? \Carbon\Carbon::parse($user->personne->datenaissance) : null;
        @endphp
        <h1>Mon Profil</h1>
        <h2>Modifier mes informations</h2>

        @if (session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

        <form method="post" action="{{ route('user.update') }}">
            @csrf
            @method('PUT')

            <h3 class="section-title">Informations personnelles</h3>
            
            <div class="form-row">
                <div class="form-group half">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $user->personne->nom) }}">
                </div>
                <div class="form-group half">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->personne->prenom) }}">
                </div>
            </div>

            <div class="form-group">
                    <label for="ville">Ville de naissance</label>
                    <input type="text" id="ville" name="naiss_ville" value="{{ old('ville', $user->personne->lieunaissance) }}">
            </div>

            <div class="form-group">
            <label for="langue">Pays de Naissance</label>
                    <select id="paysnaissance" name="pays_naissance">
                        <option value="">-- Choisir un pays de naissance --</option>
                        @foreach($nations as $nation)
                            <option value="{{ $nation->idnation ?? $nation->id }}" 
                                {{ (old('langue', $user->naiss_idnation) == ($nation->idnation ?? $nation->id)) ? 'selected' : '' }}>
                                {{ $nation->nomnation }}
                            </option>
                        @endforeach
                    </select>
            </div>

            <div class="form-group">
                <label>Date de naissance</label>
                
                @php
                    $currentDate = $user->date_naissance ? \Carbon\Carbon::parse($user->date_naissance) : null;
                    $currentDay = $currentDate ? $currentDate->day : null;
                    $currentMonth = $currentDate ? $currentDate->month : null;
                    $currentYear = $currentDate ? $currentDate->year : null;
                @endphp

                <p class="date-selects">
                    <select id="jour_naissance" name="jour_naissance" required>
                        <option value="" disabled {{ !$date ? 'selected' : '' }}>Jour</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" 
                                {{-- On compare $i avec le jour de la date ($date->day) --}}
                                {{ ($date && $date->day == $i) ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>

                    <select id="mois_naissance" name="mois_naissance" required>
                    <option value="" disabled {{ !$date ? 'selected' : '' }}>Mois</option>
                    @php
                        $mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
                                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                    @endphp
                    @foreach ($mois as $index => $nomMois)
                        <option value="{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}"
                            {{-- On compare l'index (+1 car janvier=0) avec le mois ($date->month) --}}
                            {{ ($date && $date->month == ($index + 1)) ? 'selected' : '' }}>
                            {{ $nomMois }}
                        </option>
                    @endforeach
                    </select>

                    <select id="annee_naissance" name="annee_naissance" required>
                    <option value="" disabled {{ !$date ? 'selected' : '' }}>Année</option>
                    @for ($i = date('Y'); $i >= 1920; $i--)
                        <option value="{{ $i }}" 
                            {{-- On compare $i avec l'année ($date->year) --}}
                            {{ ($date && $date->year == $i) ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </p>
        </div>

            <div class="form-group">
                <label for="pays_residence">Pays de résidence</label>
                <select id="pays_residence" name="pays_residence">
                    <option value="">-- Choisir un pays de résidence --</option>
                    @foreach($nations as $nation)
                        {{-- On compare avec $user->pays pour pré-sélectionner --}}
                        <option value="{{ $nation->idnation ?? $nation->id }}" 
                            {{ (old('pays_residence', $user->idnation) == ($nation->idnation ?? $nation->id)) ? 'selected' : '' }}>
                            {{ $nation->nomnation }}
                        </option>
                    @endforeach
                </select>
            </div>

        <div class="form-group half">
            <label for="cp">Code Postal</label>
            {{-- On pré-remplit avec la valeur de la BDD --}}
            <input type="text" id="cp" name="cp" maxlength="5" 
                value="{{ old('cp', $user->cp) }}" 
                placeholder="Ex: 75001" required>
        </div>

        <div class="form-group">
            <label for="ville_select">Ville *</label>
            <select id="ville_select" name="ville_select">
                <option value="{{ old('ville', $user->ville) }}">{{ old('ville', $user->ville) }}</option>
            </select>
            <input type="hidden" id="ville_real_name" name="ville" value="{{ old('ville', $user->ville) }}">
        </div>

            <h3 class="section-title">Compte & Préférences</h3>

            <div class="form-group">
                <label for="courriel">Courriel</label>
                <p class="hint-text">Votre identifiant de connexion</p>
                <input type="email" id="courriel" name="courriel" value="{{ old('courriel', $user->courriel) }}">
                @error('courriel')
                    <span style="color:red; font-size:0.8em;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label for="surnom">Surnom</label>
                    <input type="text" id="surnom" name="surnom" value="{{ old('surnom', $user->surnom) }}">
                </div>
                <div class="form-group half">
                    <label for="langue">Langue</label>
                    <select id="langue" name="langue">
                        <option value="">-- Choisir une langue --</option>
                        @foreach($nations as $nation)
                            <option value="{{ $nation->idnation ?? $nation->id }}" 
                                {{ (old('langue', $user->langue_idnation) == ($nation->idnation ?? $nation->id)) ? 'selected' : '' }}>
                                {{ $nation->nomnation }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="favorite">Équipe Favori (Nation)</label>
                <select id="favorite" name="favorite">
                    <option value="">-- Choisir une équipe favorite --</option>
                    @foreach($nations as $nation)
                        <option value="{{ $nation->idnation ?? $nation->id }}" 
                            {{ (old('favorite', $user->favori_idnation) == ($nation->idnation ?? $nation->id)) ? 'selected' : '' }}>
                            {{ $nation->nomnation }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            <h3 class="section-title">Sécurité</h3>
            
            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <p class="hint-text">Laisser vide pour ne pas changer</p>
                <input type="password" id="password" name="password">
                @error('password')
                    <span style="color:red; font-size:0.8em;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="button-primary">ENREGISTRER LES MODIFICATIONS</button>
        </form>
    </section>
    @endsection

@section('scripts')
<script src="{{ asset('js/main.js') }}" defer></script>
@endsection