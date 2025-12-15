@extends('layouts.app')

@section('content')

    
    {{-- OUVERTURE DU FORMULAIRE UNIQUE --}}
    <form action="{{ route('voter.store') }}" method="POST" id="voteForm">
        @csrf

        {{-- SECTION 1 : TYPE DE VOTE (Style FIFA Horizontal) --}}
        <div class="mb-10 text-center" >
            <h2 class="text-2xl font-bold text-gray-700 mb-6 uppercase tracking-widest">Type de Vote</h2>
            
            {{-- Conteneur Flex pour l'alignement horizontal --}}
            <div class="typevote"">
                @foreach($typevotes as $type)
                <label class="cursor-pointer">
    {{-- Input Radio avec la classe .type-radio --}}
    <input 
        type="radio" 
        name="idtypevote" 
        value="{{ $type->idtypevote }}" 
        class="type-radio"
        {{ (old('idtypevote') == $type->idtypevote) ? 'checked' : '' }}
    >
    
    {{-- La Div Visuelle avec la classe .type-card --}}
    <div class="type-card">
        {{ $type->nomtypevote }}
    </div>
</label>
                @endforeach
            </div>
        </div>

        <hr class="my-8 border-gray-300 w-2/3 mx-auto">

        {{-- SECTION 2 : JOUEURS --}}
        <div id="joueurs">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold text-blue-900 mb-2 uppercase">Élisez votre Top 3</h1>
                <p class="text-gray-500">Sélectionnez les joueurs dans l'ordre de préférence</p>
            </div>

            <div class="liste-joueurs-grid">
                @forelse($joueurs as $joueur)     
                <div class="div_joueurs">
                    {{-- Lien vers le profil --}}
                    <a href="{{ route('voter.show', ['id' => $joueur->idpersonne]) }}" class="link-overlay">
                        <div class="player-image-container">
                        <img src="{{ asset($joueur->destinationphoto) }}" alt="{{ $joueur->prenom }}1">
                            <div class="absolute bottom-0 left-0 bg-blue-900 text-white px-3 py-1 font-bold text-sm rounded-tr-lg">
                            {{ $joueur->numero_maillot }}
                            </div>
                        </div>
                        <div class="player-info">
                            <h2>{{ $joueur->personne->prenom }} {{ $joueur->personne->nom }}</h2>
                            <p>{{ Str::limit($joueur->biographie, 60) }}</p>
                        </div>
                    </a>

                    {{-- Zone de vote (1, 2, 3) --}}
                    <div class="vote-section">
                        @foreach(range(1, 3) as $rank)
                        <div>
                            <input type="radio" 
                                id="r{{ $rank }}_{{ $joueur->idpersonne }}" 
                                name="rank_{{ $rank }}" 
                                value="{{ $joueur->idpersonne }}" 
                                class="vote-radio" 
                                data-player="{{ $joueur->idpersonne }}"
                                {{ (old('rank_'.$rank) ?? $prefill['rank_'.$rank] ?? '') == $joueur->idpersonne ? 'checked' : '' }}
                            >
                            <label for="r{{ $rank }}_{{ $joueur->idpersonne }}" class="vote-label" title="{{ $rank }}ème place">
                                {{ $rank }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                    <div class="col-span-full text-center py-10">
                        <p class="text-xl text-gray-500">Aucun joueur disponible pour le moment.</p>
                    </div>
                @endforelse
            </div> 
        </div>

        {{-- SECTION 3 : BOUTON VALIDER (Centré avec espace) --}}
        <div id="btn_validervote">
        <button type="submit" class="btn-fifa-submit">
            <span>VALIDER MON VOTE</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </button>
        </div>

    </form> {{-- FERMETURE DU FORMULAIRE UNIQUE --}}
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logique JS pour empêcher de voter 2 fois pour le même joueur
            const allRadios = document.querySelectorAll('.vote-radio');

            allRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const clickedPlayerId = this.dataset.player;
                        const clickedRankName = this.name; // ex: rank_1

                        allRadios.forEach(otherRadio => {
                            // Si c'est le même joueur mais sur une ligne de rang différente (ex: rank_2), on décoche
                            if (otherRadio.dataset.player === clickedPlayerId && otherRadio.name !== clickedRankName) {
                                otherRadio.checked = false;
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection