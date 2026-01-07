@extends('layouts.app') 

@section('content')
<div class="vente-container">
    <h1 class="vente-title">Gestion des Catégories</h1>
    <p class="vente-subtitle">Ajouter une sous-catégorie à une catégorie existante ou créer une nouvelle structure</p>
    
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="{{ route('vente.create') }}" style="color: #034f96; text-decoration: none; font-weight: bold; border-bottom: 2px solid #034f96;">
            <i class="fas fa-arrow-left"></i> Retourner à la création de produit
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
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

    <form action="{{ route('vente.categorie.store') }}" method="POST">
        @csrf

        <div class="vente-form-grid">
            
            <div class="vente-full-width" style="border-bottom: 2px dashed #e2e8f0; padding-bottom: 20px; margin-bottom: 10px;">
                <label class="vente-label" style="color: #034f96;">ÉTAPE 1 : Choisir la Catégorie Parente</label>
                
                <div style="margin-bottom: 15px;">
                    <label class="vente-label" style="font-size: 0.85em; color: #666;">Option A : Sélectionner une existante</label>
                    <select name="idcategorie_existante" id="select_cat" class="vente-select">
                        <option value="">-- Créer une nouvelle catégorie --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->idcategorie }}">{{ $cat->nomcategorie }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="text-align: center; font-weight: bold; color: #ccc; margin: 10px 0;">- OU -</div>

                <div>
                    <label class="vente-label" style="font-size: 0.85em; color: #666;">Option B : Créer une nouvelle</label>
                    <input type="text" name="nomcategorie" id="input_cat" class="vente-input" placeholder="Ex: E-Sport" value="{{ old('nomcategorie') }}">
                </div>
            </div>

            <div class="vente-full-width">
                <label for="nomsouscategorie" class="vente-label" style="color: #034f96;">ÉTAPE 2 : Nom de la Sous-Catégorie à ajouter</label>
                <input type="text" name="nomsouscategorie" id="nomsouscategorie" class="vente-input" placeholder="Ex: Manettes" value="{{ old('nomsouscategorie') }}" required>
            </div>

            <button type="submit" class="vente-btn-submit">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('select_cat');
            const input = document.getElementById('input_cat');

            select.addEventListener('change', function() {
                if (this.value !== "") {
                    input.value = "";
                    input.disabled = true;
                    input.placeholder = "Désactivé (Catégorie existante sélectionnée)";
                    input.style.backgroundColor = "#e2e8f0";
                } else {
                    input.disabled = false;
                    input.placeholder = "Ex: E-Sport";
                    input.style.backgroundColor = "#f8fafc";
                }
            });
        });
    </script>

    <div style="margin-top: 50px; border-top: 2px solid #eee; padding-top: 30px;">
        <h3 style="color: #333; text-transform: uppercase; font-size: 1rem; margin-bottom: 20px;">Structure actuelle</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
            @foreach($structureActuelle as $item)
                <div style="background: #f8fafc; padding: 10px 15px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.9rem;">
                    <span style="color: #034f96; font-weight: bold;">{{ $item->nomcategorie }}</span>
                    <span style="color: #ccc; margin: 0 5px;">/</span>
                    <span style="color: #64748b;">{{ $item->nomsouscategorie }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection