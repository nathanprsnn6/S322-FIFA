@extends('layouts.app')

@section('content')


<section class="filter-section container">
    <div class="nation-scroll-container">
        <a href="{{ request()->fullUrlWithQuery(['nation' => null]) }}" 
           class="nation-pill {{ !$currentNation ? 'active' : '' }}">TOUS PAYS</a>
        @foreach($nations as $nation)
            <a href="{{ request()->fullUrlWithQuery(['nation' => $nation->idnation]) }}" 
               class="nation-pill {{ $currentNation == $nation->idnation ? 'active' : '' }}">
                {{ strtoupper($nation->nomnation) }}
            </a>
        @endforeach
    </div>
</section>

<nav class="filter-bar">
    <a href="{{ request()->fullUrlWithQuery(['cat' => null]) }}" 
       class="filter-link {{ !request()->filled('cat') ? 'active' : '' }}">Tout voir</a>
    @foreach($categories as $categorie)
        <a href="{{ request()->fullUrlWithQuery(['cat' => $categorie->idcategorie]) }}" 
           class="filter-link {{ request()->get('cat') == $categorie->idcategorie ? 'active' : '' }}">
            {{ $categorie->nomcategorie }}
        </a>
    @endforeach
</nav>

<div class="container" style="max-width: 1200px; margin-bottom: 30px;">
    
    <form method="GET" action="{{ url()->current() }}">

        @if(request()->filled('nation')) <input type="hidden" name="nation" value="{{ request('nation') }}"> @endif
        @if(request()->filled('cat')) <input type="hidden" name="cat" value="{{ request('cat') }}"> @endif

        <div class="advanced-filters">
            
            @if($availableSubCats->count() > 0)
            <div class="filter-group" style="width: 100%; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                <span class="filter-label">Type :</span>
                <div class="checkbox-list">
                    @foreach($availableSubCats as $sub)
                        <label class="checkbox-pill">
                            <input type="checkbox" name="subcats[]" value="{{ $sub->idsouscategorie }}" 
                                {{ in_array($sub->idsouscategorie, $selectedSubCats) ? 'checked' : '' }}>
                            <span>{{ $sub->nomsouscategorie }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Groupe Couleurs --}}
            <div class="filter-group">
                <span class="filter-label">Couleurs :</span>
                <div class="checkbox-list">
                    @foreach($allColors as $color)
                        <label class="checkbox-pill">
                            <input type="checkbox" name="colors[]" value="{{ $color->idcoloris }}" 
                                {{ in_array($color->idcoloris, $selectedColors) ? 'checked' : '' }}>
                            <span>{{ $color->libellecoloris }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Groupe Tailles --}}
            <div class="filter-group">
                <span class="filter-label">Tailles :</span>
                <div class="checkbox-list">
                    @foreach($allSizes as $size)
                        <label class="checkbox-pill">
                            <input type="checkbox" name="sizes[]" value="{{ $size->idtaille }}"
                                {{ in_array($size->idtaille, $selectedSizes) ? 'checked' : '' }}>
                            <span>{{ $size->tailleproduit }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="filter-actions">
        <a href="{{ url()->current() }}?{{ http_build_query(request()->only(['cat', 'nation'])) }}" 
   style="color: #034f96; font-weight: bold;">
   Réinitialiser les filtres</a>
            <button type="submit" class="btn-apply">APPLIQUER LES FILTRES</button>

            <select name="sort" onchange="this.form.submit()" class="sort-select">
                <option value="">Trier par prix...</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Prix croissant</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Prix décroissant</option>
            </select>
        </div>

    </form>
</div>

<div id="produits">
    @forelse($produits as $produit)
        {{-- On retire le <a> qui englobait tout pour faire du DIV l'élément principal de la grille --}}
        <div class="div_produit" style="position: relative;">
            
            {{-- Lien vers le détail (Partie cliquable client) --}}
            <a href="{{ route('produit.show', ['id' => $produit->idproduit]) }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; width: 100%; flex-grow: 1;">
                
                <img src="{{ asset($produit->destinationphoto) }}" alt="{{ $produit->titreproduit }}" id="img_maillot">
                
                <h2>{{ $produit->titreproduit }}</h2>
                
                <p>
                    @if(isset($produit->min_prix) && isset($produit->max_prix) && $produit->min_prix != $produit->max_prix)
                        <span style="font-size: 0.8em; color: #666;">À partir de</span> <br>
                        <strong>{{ number_format($produit->min_prix, 2) }}€</strong>
                    @else
                        <strong>{{ number_format($produit->min_prix ?? $produit->prix ?? 0, 2) }}€</strong>
                    @endif
                </p> 
            </a>

            {{-- BOUTON MODIFIER (Visible uniquement pour le Service Vente - ID 5) --}}
            @auth
                @if(Auth::user()->idrole == 5)
                    <div style="width: 100%; margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
                        <a href="{{ route('vente.edit', $produit->idproduit) }}" 
                           style="display: block; background-color: #e67e22; color: white; padding: 8px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9em; transition: 0.2s;">
                           <i class="fas fa-pen"></i> Modifier le produit
                        </a>
                    </div>
                @endif
            @endauth

        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <h3>Aucun résultat</h3>
            <p>Essayez de modifier vos filtres.</p>
            <a href="{{ url()->current() }}" style="color: #034f96; font-weight: bold;">Réinitialiser tout</a>
        </div>
    @endforelse
</div>

@endsection

