@extends('layouts.app')

@section('content')
    <h1>Liste des Personnes</h1>

    <ul>
        @foreach($personnes as $personne)
            {{-- Affiche le Prénom et le Nom --}}
            <li>
                {{ $personne->prenom }} {{ $personne->nom }} 
                (Né à : {{ $personne->lieunaissance }})
            </li>
        @endforeach
    </ul>
@endsection
 