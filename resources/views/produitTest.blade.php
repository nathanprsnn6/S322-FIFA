<head>
    <link rel="stylesheet" href="{{ asset('/style.css') }}">
</head>
@extends('layouts.app')

@section('content')
    <h1>Liste des Personnes</h1>

    <ul>
        <div id="produits">
            @foreach($produits as $produit)
                {{-- Affiche le Prénom et le Nom --}}
                <div class="div_produit">
                    <h2>{{ $produit->titreproduit }}</h2>
                    <p>60,00€</p>
                </div>

            @endforeach
        </div>
    </ul>
@endsection