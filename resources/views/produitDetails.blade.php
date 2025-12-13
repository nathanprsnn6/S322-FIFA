@extends('layouts.app')

@section('content')

<script>
    window.stockData = @json($stock);
</script>

<div id="double_img">
    <div>
        <img id="img_detail" src="{{ asset($photo->destinationphoto) }}" alt="{{ $produit->titreproduit }}">
    </div>
    <form action="{{ route('produits.index') }}" method="POST">
        @csrf
        <input type="hidden" name="produitId" value="{{ $produit->idproduit }}">
        <div id="product-info">
            <h1>{{ $produit->titreproduit }}</h1>

            <p class="text_detail">
                <b>Description :</b><br>
                {{ $produit->descriptionproduit }}
            </p>

            <div>
                <div class="size-buttons-wrapper">
                <label id="lab_size" class="text_detail"><b>Taille :</b></label>
                    @foreach($tailles as $taille)
                        <input type="radio" class="size-input" id="size_{{ $taille->idtaille }}" name="size" value="{{ $taille->idtaille }}" style="display:none;">
                        <label class="size-button" for="size_{{ $taille->idtaille }}">
                            {{ $taille->tailleproduit }}
                        </label>
                    @endforeach
                </div>

                <label id="lab_color" class="text_detail" for="color"><b>Couleur :</b></label>
                <select class="text_detail" id="color" name="color">
                    @foreach($variantes as $variante)
                        <option value="{{ $variante->idcoloris }}" data-price="{{ $variante->prixproduit }}">
                            {{ $variante->libellecoloris }}
                        </option>
                    @endforeach
                </select>
            </div>

            <br>

            <label id="lab_quantity" class="text_detail" for="quantity"><b>Quantité :</b></label>
            <input type="number" id="quantity" name="quantity" min="0" max="{{ $maxQuantity }}" value="1" required>

            <p id="price">
                <b>{{ number_format($variantes->first()->prixproduit ?? 0, 2) }} €</b>
            </p>
            <br>
            <div id="stock" class="text_detail" style="font-weight: bold;">
                Sélectionnez une taille
            </div>
            <br>
            <div class="add-cart">
                <button type="submit" id="add-button" class="text_detail" disabled>Ajouter au panier</button>
            </div>
        </div>
    </form>
</div>

@if($produitsSimilaires->count() > 0)
    <div class="container" style="max-width: 100%; background: none; box-shadow: none; padding: 0 20px; margin-top: 50px;">
        <h3 class="section-title" style="font-size: 1.5em; border-bottom: 1px solid #ddd; padding-bottom: 15px;">
            Vous aimerez aussi
        </h3>
    </div>

    <div id="produits">
        @foreach($produitsSimilaires as $similaire)
            <div class="div_produit">
                <a href="{{ url('/produit/' . $similaire->idproduit) }}">
                    <img src="{{ asset($similaire->destinationphoto) }}" 
                         alt="{{ $similaire->titreproduit }}" 
                         style="height: 180px; width: auto; object-fit: contain; margin-bottom: 10px;">
                    
                    <h4 style="font-size: 1em; color: #333; margin: 10px 0; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ $similaire->titreproduit }}
                    </h4>

                    <span style="font-size: 1.1em; font-weight: bold; color: #034f96;">
                        {{ number_format($similaire->prix, 2, ',', ' ') }} €
                    </span>
                </a>
            </div>
        @endforeach
    </div>
@endif

@section('scripts')
<script src="{{ asset('js/main.js') }}" defer></script>
@endsection

@endsection