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
        <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="vente-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="margin-top:0; border-bottom: 2px solid #eee; padding-bottom: 10px;">1. Informations Générales</h2>
        <form action="{{ route('vente.update', $produit->idproduit) }}" method="POST">
            @csrf
            @method('PUT')
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
                            <option value="{{ $nation->idnation }}" {{ $nation->idnation == $produit->idnation ? 'selected' : '' }}>{{ $nation->nomnation }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="vente-label">Compétition</label>
                    <select name="idcompetition" class="vente-select" required>
                        @foreach($competitions as $comp)
                            <option value="{{ $comp->idcompetition }}" {{ $comp->idcompetition == $produit->idcompetition ? 'selected' : '' }}>{{ $comp->nomcompetition }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vente-full-width" style="text-align: right;">
                    <button type="submit" class="vente-btn-submit">Mettre à jour les infos</button>
                </div>
            </div>
        </form>
    </div>

    <div class="vente-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <h2 style="margin-top:0; border-bottom: 2px solid #eee; padding-bottom: 10px;">2. Images du produit</h2>
        
        <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 20px;">
            @foreach($photos as $photo)
                <div style="position: relative; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                    <img src="{{ asset($photo->destinationphoto) }}" style="height: 100px; width: auto; object-fit: contain;">
                    
                    @if(count($photos) > 1)
                        <form action="{{ route('vente.image.delete', ['id' => $produit->idproduit, 'idPhoto' => $photo->idphoto]) }}" method="POST" onsubmit="return confirm('Supprimer cette image ?');" style="position: absolute; top: -5px; right: -5px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <form action="{{ route('vente.image.add', $produit->idproduit) }}" method="POST" enctype="multipart/form-data" style="background: #f9f9f9; padding: 15px; border-radius: 4px;">
            @csrf
            <label class="vente-label">Ajouter une image :</label>
            <div style="display: flex; gap: 10px;">
                <input type="file" name="photo" class="vente-input" required>
                <button type="submit" class="vente-btn-submit" style="margin: 0; white-space: nowrap;">Uploader</button>
            </div>
        </form>
    </div>

    <div class="vente-section" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="margin-top:0; border-bottom: 2px solid #eee; padding-bottom: 10px;">3. Variantes (Couleurs & Prix)</h2>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background: #f1f5f9; text-align: left;">
                    <th style="padding: 10px;">Couleur</th>
                    <th style="padding: 10px;">Prix</th>
                    <th style="padding: 10px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($variantes as $var)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px;">
                        <span style="display: inline-block; width: 15px; height: 15px; background-color: {{ $var->hexacoloris }}; border: 1px solid #ccc; border-radius: 50%; vertical-align: middle; margin-right: 5px;"></span>
                        {{ $var->libellecoloris }}
                    </td>
                    <td style="padding: 10px;">{{ number_format($var->prixproduit, 2) }} €</td>
                    <td style="padding: 10px; text-align: right;">
                        <form action="{{ route('vente.variant.delete', ['id' => $produit->idproduit, 'idColoris' => $var->idcoloris]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ? Cela supprimera le stock associé.');">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($colorisDisponibles->isNotEmpty())
        <div style="background: #f9f9f9; padding: 15px; border-radius: 4px;">
            <h4 style="margin-top: 0;">Ajouter une variante</h4>
            <form action="{{ route('vente.variant.add', $produit->idproduit) }}" method="POST">
                @csrf
                <div class="vente-form-grid" style="grid-template-columns: 1fr 1fr auto;">
                    <div>
                        <label class="vente-label">Couleur</label>
                        <select name="idcoloris" class="vente-select" required>
                            @foreach($colorisDisponibles as $col)
                                <option value="{{ $col->idcoloris }}">{{ $col->libellecoloris }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="vente-label">Prix (€)</label>
                        <input type="number" step="0.01" name="prixproduit" class="vente-input" required placeholder="0.00">
                    </div>
                    <div style="display: flex; align-items: flex-end;">
                        <button type="submit" class="vente-btn-submit" style="margin: 0;">Ajouter</button>
                    </div>
                </div>
                <small style="color: #666;">Note: Le stock sera initialisé à 0 pour toutes les tailles.</small>
            </form>
        </div>
        @else
            <p>Toutes les couleurs disponibles sont déjà utilisées pour ce produit.</p>
        @endif
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        document.getElementById('idcategorie').addEventListener('change', function() {
            var idCategorie = this.value;
            var sousCategorieSelect = document.getElementById('idsouscategorie');
            
            sousCategorieSelect.innerHTML = '<option value="">Chargement...</option>';
            
            fetch('/vente/sous-categories/' + idCategorie)
                .then(response => response.json())
                .then(data => {
                    sousCategorieSelect.innerHTML = '';
                    data.forEach(function(sc) {
                        var option = document.createElement('option');
                        option.value = sc.idsouscategorie;
                        option.text = sc.nomsouscategorie;
                        sousCategorieSelect.add(option);
                    });
                });
        });
    </script>
@endsection