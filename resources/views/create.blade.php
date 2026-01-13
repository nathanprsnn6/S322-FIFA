@extends('layouts.app') 

@section('content')
<div class="vente-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 10px;">
        <h1 class="vente-title" style="margin-bottom: 0;">Mise en Vente</h1>
        
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('vente.invisible.index') }}" style="background-color: #64748b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-eye-slash"></i> Produits en attente
            </a>

            <a href="{{ route('vente.demandes.index') }}" style="background-color: #d97706; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fas fa-envelope-open-text"></i> Voir les demandes Pro
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('error'))
    <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <b>Erreur :</b> {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('vente.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="vente-form-grid">
            
            <div class="vente-full-width">
                <label for="titreproduit" class="vente-label">Titre du produit</label>
                <input type="text" name="titreproduit" id="titreproduit" class="vente-input" placeholder="Ex: Maillot France 2026" value="{{ old('titreproduit') }}" required>
            </div>

            <div class="vente-full-width">
                <label for="descriptionproduit" class="vente-label">Description</label>
                <textarea name="descriptionproduit" class="vente-textarea" required>{{ old('descriptionproduit') }}</textarea>
            </div>

            <div class="vente-full-width">
                <label class="vente-label">Catégorisation</label>
                <div class="vente-full-width">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <label class="vente-label" style="margin-bottom: 0;">Catégorisation</label>
                    <a href="{{ route('vente.categorie.create') }}" style="font-size: 0.9rem; color: #034f96; text-decoration: none; font-weight: 700; border: 1px solid #034f96; padding: 5px 10px; border-radius: 4px; transition: all 0.2s;">
                        <i class="fas fa-plus"></i> Créer une catégorie
                    </a>
                </div>
                
                <div class="vente-select-group">
                    <select name="idcategorie" id="idcategorie" class="vente-select" required>
                        <option value="">1. Sélectionner une catégorie...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->idcategorie }}" {{ old('idcategorie') == $cat->idcategorie ? 'selected' : '' }}>
                                {{ $cat->nomcategorie }}
                            </option>
                        @endforeach
                    </select>

                    <select name="idsouscategorie" id="idsouscategorie" class="vente-select" required disabled>
                        <option value="">En attente de catégorie...</option>
                    </select>
                </div>
            </div>


            <div>
                <label class="vente-label">Nation</label>
                <select name="idnation" class="vente-select" required>
                    <option value="">Choisir...</option>
                    @foreach($nations as $nation)
                        <option value="{{ $nation->idnation }}" {{ old('idnation') == $nation->idnation ? 'selected' : '' }}>
                            {{ $nation->nomnation }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="vente-label">Compétition</label>
                <select name="idcompetition" class="vente-select" required>
                    <option value="">Choisir...</option>
                    @foreach($competitions as $comp)
                        <option value="{{ $comp->idcompetition }}" {{ old('idcompetition') == $comp->idcompetition ? 'selected' : '' }}>
                            {{ $comp->nomcompetition }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="vente-label">Prix (€)</label>
                <input type="number" step="0.01" name="prixproduit" class="vente-input" value="{{ old('prixproduit') }}" required>
            </div>

            <div>
                <label class="vente-label">Couleur</label>
                <select name="idcoloris" class="vente-select" required>
                    <option value="">Choisir...</option>
                    @foreach($coloris as $col)
                        <option value="{{ $col->idcoloris }}" {{ old('idcoloris') == $col->idcoloris ? 'selected' : '' }}>
                            {{ $col->libellecoloris }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="vente-full-width">
                <label class="vente-label">Photo (sera renommée automatiquement)</label>
                <input type="file" name="photo" class="vente-file-upload" required>
            </div>

            <div class="vente-full-width">
                <label class="vente-label">Stocks</label>
                <div style="display: flex; gap: 10px; justify-content: space-between; flex-wrap: wrap;">
                    @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $taille)
                        <div style="text-align: center;">
                            <label style="font-size: 0.8em; font-weight: bold;">{{ $taille }}</label>
                            <input type="number" name="stock_{{ strtolower($taille) }}" value="{{ old('stock_'.strtolower($taille), 0) }}" min="0" style="width: 50px; padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="vente-btn-submit">Mettre en vente (Invisible)</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        document.getElementById('idcategorie').addEventListener('change', function() {
            var idCategorie = this.value;
            var sousCategorieSelect = document.getElementById('idsouscategorie');
            var sousCatContainer = sousCategorieSelect.parentElement;

            if(idCategorie) {
                sousCategorieSelect.disabled = false;
                sousCategorieSelect.innerHTML = '<option value="">Chargement...</option>';
                
                fetch('/api/sous-categories/' + idCategorie)
                    .then(response => response.json())
                    .then(data => {
                        sousCategorieSelect.innerHTML = '<option value="">Choisir une sous-catégorie...</option>';
                        data.forEach(function(sc) {
                            var option = document.createElement('option');
                            option.value = sc.idsouscategorie;
                            option.text = sc.nomsouscategorie;
                            sousCategorieSelect.add(option);
                        });
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        sousCategorieSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    });
            } else {
                sousCategorieSelect.disabled = true;
                sousCategorieSelect.innerHTML = '<option value="">En attente de catégorie...</option>';
            }
        });
    </script>
@endsection