@extends('layouts.app')

@section('content')
<div class="search-header">
    <div class="header-content">
        <form action="{{ url('/publication') }}" method="GET" class="search-form">
            <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
            </div>
        </form>
    </div>
</div>

<div class="container-publications">
    <h1>Liste des Publications</h1>

    @forelse($publications as $pub)
        <a href="{{ url('publication/'.$pub->idpublication) }}" class="pub-card">
            <div class="pub-visual">
                {{-- ICI LA LOGIQUE DE DÉTECTION --}}
                @if($pub->blog)
                    <span class="pub-type" style="background-color: #e67e22;">Blog</span>
                @else
                    <span class="pub-type">Article</span>
                @endif

                <div class="pub-image">
                    @if($pub->photo)
                        <img src="{{ asset($pub->photo->destinationphoto) }}" alt="Image">
                    @else
                        <p>Aucune image</p>
                    @endif
                </div>
            </div>

            <div class="pub-content">
                <p class="pub-date">{{ \Carbon\Carbon::parse($pub->datepublication)->translatedFormat('d M Y') }}</p>
                <h2 class="pub-title">{{ $pub->titrepublication }}</h2>
                <p class="pub-excerpt">{{ Str::limit($pub->resumepublication, 150) }}</p>
            </div>
        </a>
    @empty
        <p>Aucun résultat trouvé.</p>
    @endforelse
</div>
@endsection