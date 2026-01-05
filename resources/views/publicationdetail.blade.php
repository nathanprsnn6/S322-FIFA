@extends('layouts.app')

@section('content')
<div class="article-container">
    
    <header class="article-header">
        <h1 class="main-title">{{ $publication->titrepublication }}</h1>
    </header>

    <div class="article-hero">
         @if($publication->photo)
            <img src="{{ asset($publication->photo->destinationphoto) }}" alt="Image de publication">
        @else
            <p>Aucune image disponible</p>
        @endif
    </div>

    <div class="article-body">
        <div class="article-meta">
            <span class="pub-date">
                Publié le {{ \Carbon\Carbon::parse($publication->datepublication)->translatedFormat('d F Y') }}
            </span>
        </div>

        <div class="article-text">
            {{-- Le texte long provenant de la table 'article' --}}
            @if($article)
                {!! nl2br(e($article->textarticle)) !!}
            @else
                <p>Aucun contenu détaillé disponible pour cet article.</p>
            @endif
        </div>
    </div>

</div>
@endsection