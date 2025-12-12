@extends('layouts.app')

@section('content')

<div id="joueurs"> <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-blue-900 mb-2">Élisez votre Top 3</h1>
    </div>

    <form action="{{ route('voter.store') }}" method="POST" id="voteForm">
        @csrf
        
        <div class="liste-joueurs-grid">

            @forelse($joueurs as $joueur)
                
            <div class="div_joueurs">
                <a href="{{ route('voter.show', ['id' => $joueur->idpersonne]) }}" class="link-overlay">
                    <div class="player-image-container">
                        <img src="{{ asset('img/joueur-test.jpg') }}" alt="{{ $joueur->personne->prenom }}">
                    </div>
                    <div class="player-info">
                        <h2>{{ $joueur->personne->prenom }} {{ $joueur->personne->nom }}</h2>
                        <p>{{ Str::limit($joueur->biographie, 60) }}</p>
                    </div>
                </a>

                <div class="vote-section">
                    <div>
                        <input type="radio" id="r1_{{ $joueur->idpersonne }}" name="rank_1" value="{{ $joueur->idpersonne }}" class="vote-radio" data-player="{{ $joueur->idpersonne }}">
                        <label for="r1_{{ $joueur->idpersonne }}" class="vote-label" title="1ère place">1</label>
                    </div>
                    
                    <div>
                        <input type="radio" id="r2_{{ $joueur->idpersonne }}" name="rank_2" value="{{ $joueur->idpersonne }}" class="vote-radio" data-player="{{ $joueur->idpersonne }}">
                        <label for="r2_{{ $joueur->idpersonne }}" class="vote-label" title="2ème place">2</label>
                    </div>

                    <div>
                        <input type="radio" id="r3_{{ $joueur->idpersonne }}" name="rank_3" value="{{ $joueur->idpersonne }}" class="vote-radio" data-player="{{ $joueur->idpersonne }}">
                        <label for="r3_{{ $joueur->idpersonne }}" class="vote-label" title="3ème place">3</label>
                    </div>
                </div>
            </div>

            @empty
                <p>Aucun joueur disponible pour le moment.</p>
            @endforelse
            
        </div> 
        <div class="text-center mt-12 mb-8">
            <button type="submit" class="btn-vote-submit bg-blue-900 text-white px-8 py-3 rounded-full font-bold shadow-xl hover:bg-blue-800 transition transform hover:scale-105 inline-flex items-center gap-2">
                <span>Valider mon vote</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allRadios = document.querySelectorAll('.vote-radio');

            allRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const clickedPlayerId = this.dataset.player;
                        const clickedRankName = this.name;

                        allRadios.forEach(otherRadio => {
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