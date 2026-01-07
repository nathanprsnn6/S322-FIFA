@extends('layouts.app') 

@section('content')
<div class="vente-container">
    <h1 class="vente-title">Modifier le Produit #{{ $produit->idproduit }}</h1>
    
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="{{ route('produits.index') }}" style="color: #64748b; text-decoration: none;">
            <i class="fas fa-arrow-left"></i> Retour au catalogue
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #a3d9b1;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f87171;">
            <i class="fas fa-exclamation-triangle"></i> <b>Erreur :</b> {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error) 
                    <li>{{ $error }}</li> 
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vente.update', $produit->idproduit) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <input type="hidden" name="old_idcoloris" value="{{ $variante->idcoloris }}">

        <div class="vente-form-grid">
            
            <div class="vente-full-width">
                <label class="vente-label">Titre</label>
                <input type="text" name="titreproduit" class="vente-input" value="{{ $produit->titreproduit }}" required>
            </div>

            <div class="vente-full-width">
                <label class="vente-label">Description</label>
                <textarea name="descriptionproduit" class="vente-textarea" required>{{ $produit->descriptionproduit }}</textarea>
            </div>

            <div class="vente-full-width">
                <label class="vente-label">Catégorisation</label>
                <div class="vente-select-group">
                    <select name="idcategorie" id="idcategorie" class="vente-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->idcategorie }}" {{ $cat->idcategorie == $idCategorieActuelle ? 'selected' : '' }}>
                                {{ $cat->nomcategorie }}
                            </option>
                        @endforeach
                    </select>

                    <select name="idsouscategorie" id="idsouscategorie" class="vente-select" required>
                        @foreach($sousCategoriesActuelles as $sc)
                            <option value="{{ $sc->idsouscategorie }}" {{ $sc->idsouscategorie == $produit->idsouscategorie ? 'selected' : '' }}>
                                {{ $sc->nomsouscategorie }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="vente-label">Nation</label>
                <select name="idnation" class="vente-select" required>
                    @foreach($nations as $nation)
                        <option value="{{ $nation->idnation }}" {{ $nation->idnation == $produit->idnation ? 'selected' : '' }}>
                            {{ $nation->nomnation }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="vente-label">Compétition</label>
                <select name="idcompetition" class="vente-select" required>
                    @foreach($competitions as $comp)
                        <option value="{{ $comp->idcompetition }}" {{ $comp->idcompetition == $produit->idcompetition ? 'selected' : '' }}>
                            {{ $comp->nomcompetition }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="vente-label">Prix (€)</label>
                <input type="number" step="0.01" name="prixproduit" class="vente-input" value="{{ $variante->prixproduit }}" required>
            </div>

            <div>
                <label class="vente-label">Couleur</label>
                <select name="idcoloris" class="vente-select" required>
                    @foreach($coloris as $col)
                        <option value="{{ $col->idcoloris }}" {{ $col->idcoloris == $variante->idcoloris ? 'selected' : '' }}>
                            {{ $col->libellecoloris }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="vente-btn-submit">Enregistrer les modifications</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endsection