@extends('layouts.app')

@section('content')
    <div class="player-container">
        
        {{-- En-tête : Photo (Gauche) + Infos (Droite) --}}
        <div class="player-header">
            
            {{-- Photo --}}
            <div class="player-photo-wrapper">
                <img 
                    src="{{ asset($joueur->destinationphoto) }}" 
                    alt="{{ $joueur->prenom }}" 
                    class="player-photo"
                >
            </div>

            {{-- Informations --}}
            <div class="player-info-wrapper">
                <h1 class="player-name">
                    {{ $joueur->personne->prenom }} {{ $joueur->personne->nom }}
                </h1>
                
                <div class="player-details-grid">
                    <div class="detail-column">
                        <p><span class="detail-label">Poste :</span> Attaquant</p>
                        <p><span class="detail-label">Date de naissance :</span> {{ $joueur->personne->datenaissance }}</p>
                        <p><span class="detail-label">Lieu de naissance :</span> {{ $joueur->personne->lieunaissance }}</p>
                        <p><span class="detail-label">Pied préféré :</span> {{ $joueur->piedprefere }}</p>
                        <p><span class="detail-label">Club:</span> {{ $equipe->libelleequipe }}</p>
                    </div>

                    <div class="detail-column">
                        <p><span class="detail-label">1ère sélection :</span> {{ $playerData->premiere_selection_date }}: {{ $nation?->nomnation }} {{ $playerData->premiere_selection_score }} {{ $playerData->premiere_selection_adversaire }}</p>
                        <p><span class="detail-label">Nombre de sélections :</span> {{ $playerData->nb_selections }}</p>
                        <p><span class="detail-label">Poids :</span> {{ $joueur->poids }} kg</p>
                        <p><span class="detail-label">Taille :</span> {{ $joueur->taille }} cm</p>
                    </div>
                </div>
            </div>

        </div>

        <hr class="player-separator">

        {{-- Section Biographie --}}
        <div class="player-bio-section">
            <h2 class="section-title">BIOGRAPHIE</h2>
            <div class="bio-content">
                @if(isset($player['bio']))
                    @foreach($player['bio'] as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                @else
                    <p>{{ $joueur->biographie }}</p>
                @endif
            </div>
        </div>

        {{-- Section Statistiques --}}
        <div class="stats-section">
            <h2 class="section-title mb-6">STATISTIQUES</h2>
            
            <div class="stats-grid">
                @php
                    $statsDisplay = [
                        ['label' => 'Matchs joués', 'value' => $playerData->matchs_joues ?? 0],
                        ['label' => 'Titularisations', 'value' => $playerData->titularisations ?? 0],
                        ['label' => 'Minutes jouées', 'value' => $playerData->minutes_jouees ?? 0],
                        ['label' => 'Buts', 'value' => $playerData->buts ?? 0]
                    ];
                @endphp

                @foreach($statsDisplay as $stat)
                <div class="hexagon-wrapper">
                    <div class="hexagon">
                        <div class="hexagon-inner stat-content">
                            <span class="stat-label">{{ $stat['label'] }}</span>
                            <span class="stat-value">{{ $stat['value'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection