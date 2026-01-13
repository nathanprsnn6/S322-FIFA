@extends('layouts.app')

@section('content')
<div class="article-container">
    
    <header class="article-header">
        <h1 class="main-title">{{ $publication->titrepublication }}</h1>
    </header>

    <div class="article-hero">
        @if($publication->photo)
            <img src="{{ asset($publication->photo->destinationphoto) }}" alt="Image">
        @endif
    </div>

    <div class="article-body">
        <div class="article-meta">
            Publié le {{ \Carbon\Carbon::parse($publication->datepublication)->translatedFormat('d F Y') }}
        </div>

      <div class="article-text">
    {{-- On vérifie si la relation 'blog' existe sur la publication --}}
    @if($publication->blog)
        <div class="blog-content">
            {!! nl2br(e($publication->blog->textarticle)) !!}
        </div>

        <div class="comments-section" style="margin-top: 50px;">
            <h3>Commentaires ({{ $publication->blog->commentaires->count() }})</h3>
            
            @forelse($publication->blog->commentaires as $com)
                <div class="comment" style="border-bottom: 1px solid #eee; padding: 10px 0;">
                    <strong>{{ $com->personne->prenom ?? 'Anonyme' }}</strong>
                    <p>{{ $com->textecommentaire }}</p>
                </div>
            @empty
                <p>Aucun commentaire pour le moment.</p>
            @endforelse
            
            {{-- Formulaire d'ajout... --}}
            <div class="mt-4">
    <h4>Laisser un commentaire</h4>
    
    @auth
        <form action="{{ route('commentaires.store', $publication->idpublication) }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea name="textecommentaire" class="form-control" rows="3" placeholder="Votre message..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Envoyer le commentaire</button>
        </form>
    @else
        <div class="alert alert-info">
            Veuillez vous <a href="{{ route('login') }}">connecter</a> pour laisser un commentaire.
        </div>
    @endauth
</div>
        </div>

    {{-- Sinon on vérifie si c'est un article --}}
    @elseif($publication->article)
        <div class="article-content">
            {!! nl2br(e($publication->article->textarticle)) !!}
        </div>
    @else
        <p>Contenu introuvable.</p>
    @endif
</div>
</div>
@endsection