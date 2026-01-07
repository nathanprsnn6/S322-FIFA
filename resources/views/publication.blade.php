@extends('layouts.app')

@section('content')
<div class="search-header">
    <div class="header-content">
        <form action="{{ url('/publication') }}" method="GET" class="search-form">
            <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" placeholder="Rechercher des vidéos, des joueurs..." value="{{ request('search') }}">
            </div>
        </form>
    </div>
</div>

<div class="container-publications">
    <h1>Liste des Publications</h1>

    @forelse($publication as $unePublication)
        <a href="{{ url('publication/'.$unePublication->idpublication) }}" class="pub-card">
            <div class="pub-visual">
                <span class="pub-type">Article</span>
                <div class="pub-image">
                    @if($unePublication->photo)
                        <img src="{{ $unePublication->photo->destinationphoto }}" alt="Image de publication">
                    @else
                        <p>Aucune image disponible</p>
                    @endif
                </div>
            </div>

            <div class="pub-content">
                <p class="pub-date">
                    {{ \Carbon\Carbon::parse($unePublication->datepublication)->translatedFormat('d M Y') }}
                </p>
                <h2 class="pub-title">
                    {{ $unePublication->titrepublication }}
                </h2>
                <p class="pub-excerpt">
                    {{ Str::limit($unePublication->resumepublication, 150) }}
                </p>
            </div>
        </a>
    @empty
        <p>Aucun résultat trouvé pour "{{ request('search') }}".</p>
    @endforelse
</div>
@endsection