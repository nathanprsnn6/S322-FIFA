@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin-top: 50px;">
    <h1 style="color: #b91c1c; margin-bottom: 30px;">Création Produit (Bureau d'étude)</h1>

    @if(session('error'))
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <b>Erreur :</b> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
            <ul>
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05);">
        <form action="{{ route('vente.demandes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="iddemandeproduit" value="{{ $demande->iddemandeproduit }}">

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Titre du produit</label>
                <input type="text" name="titreproduit" value="{{ $demande->sujet }}" required
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Description (Basée sur la demande)</label>
                <textarea name="descriptionproduit" rows="5" required
                          style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">{{ $demande->message }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Nation</label>
                    <select name="idnation" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        @foreach($nations as $nation)
                            <option value="{{ $nation->idnation }}">{{ $nation->nomnation }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-weight: bold; display: block; margin-bottom: 5px;">Compétition</label>
                    <select name="idcompetition" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                        @foreach($competitions as $comp)
                            <option value="{{ $comp->idcompetition }}">{{ $comp->nomcompetition }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Sous-Catégorie</label>
                <select name="idsouscategorie" id="sousCatSelect" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="">Sélectionner...</option>
                    @foreach($categories as $cat)
                        <optgroup label="{{ $cat->nomcategorie }}">
                            @php
                                $sousCats = DB::table('sous_categorie')->where('idcategorie', $cat->idcategorie)->get();
                            @endphp
                            @foreach($sousCats as $sc)
                                <option value="{{ $sc->idsouscategorie }}">{{ $sc->nomsouscategorie }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 30px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px;">Images du produit (Sélection multiple possible)</label>
                <input type="file" name="photos[]" multiple required accept="image/*"
                       style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f9f9f9;">
                <small style="color: #666;">Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs images.</small>
            </div>

            <div style="background: #fff1f2; color: #9f1239; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 0.9em;">
                <i class="fas fa-info-circle"></i> Le produit sera créé avec un prix de <strong>0.00 €</strong> pour signalement au Bureau d'Étude.
            </div>

            <button type="submit" style="background-color: #b91c1c; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">
                Créer et Transmettre
            </button>
        </form>
    </div>
</div>
@endsection