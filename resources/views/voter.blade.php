@extends('layouts.app')

@section('content')

<div id="joueurs">
    @forelse($joueurs as $joueur)
        
        <a href="{{ route('voter.show', ['id' => $joueur->idpersonne]) }}">
            <div class="div_joueurs">
            <img src="{{ asset('img/joueur-test.jpg') }}" alt="joueur-test" id="img_joueur">
            <h2>{{ $joueur->personne->prenom }} {{ $joueur->personne->nom }}</h2>
                <p>{{Str::limit($joueur->biographie, 50) }}</p> </div>
        </a>

    @empty
        <p>Aucun joueur disponible pour le moment.</p>
    @endforelse
</div>

@endsection


