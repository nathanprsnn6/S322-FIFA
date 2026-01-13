@extends('layouts.app')

@section('content')
<div class="container mt-5">
    {{-- Header --}}
    <div class="fifa-header-container d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fifa-title m-0">ÉLIGIBILITÉ : {{ $type->nomtypevote }}</h1>
            <p class="text-muted ms-4 mb-0">Cochez les joueurs autorisés pour ce vote.</p>
        </div>
    </div>

    <form action="{{ route('typesvote.store_joueurs', $type->idtypevote) }}" method="POST" id="eligibilityForm">
        @csrf
        
        <div class="player-selection-list">
            @foreach($joueurs as $joueur)
                {{-- Ligne cliquable --}}
                <label class="player-selection-row @if(in_array($joueur->idpersonne, $eligibleIds)) active @endif">
                    
                    {{-- Nom du joueur à gauche --}}
                    <div class="player-name-pro">
                        {{ $joueur->personne->prenom }} {{ $joueur->personne->nom }}
                    </div>

                    {{-- Grosse case à cocher à droite --}}
                    <input type="checkbox" name="joueurs[]" value="{{ $joueur->idpersonne }}" 
                           class="fifa-checkbox"
                           @if(in_array($joueur->idpersonne, $eligibleIds)) checked @endif
                           onchange="toggleRowActive(this)">
                </label>
            @endforeach
        </div>
                <div class="d-flex gap-3">
            <a href="{{ route('typesvote.index') }}" class="btn btn-outline-secondary btn-action">Annuler</a>
            <button type="submit" form="eligibilityForm" class="btn btn-fifa-primary">
                <i class="fas fa-save me-2"></i> ENREGISTRER
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function toggleRowActive(checkbox) {
        // Ajoute ou retire la classe active à la ligne parente (label)
        if (checkbox.checked) {
            checkbox.parentElement.classList.add('active');
        } else {
            checkbox.parentElement.classList.remove('active');
        }
    }
</script>
@endsection