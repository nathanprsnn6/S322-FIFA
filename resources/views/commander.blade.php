@extends('layouts.app')

@section('content')

<div class="commande-page-container">
    <div class="form-column">
        <div class="container commande-form-box">
            <h2 style="text-align: center; color: #034f96;">
                Commande
                <div class="tooltip-container">
                    <div class="info-icon">i</div>
                    <div class="tooltip-box">
                        Ces donnée serviron à nos partenaire tiers pour livrer vos colis chez vous.
                    </div>
                </div>
            </h2>
            
            <hr>
            
            <form id="commande-form" method="POST" action="{{ route('payer.processPayment') }}">    
                @csrf

                {{-- Étape 1 : Coordonnées et Adresse --}}
                <div id="coordonnees" class="formulaireLivraison active">
                    <h3>1. Informations de Contact et Adresse</h3>
                    
                    <label for="email">Courriel *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$" placeholder="Entrez votre courriel" class="form-control">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    
                    <label for="nom_complet">Nom complet *</label>
                    <input type="text" name="nom_complet" id="nom_complet" value="{{ old('nom_complet') }}" required placeholder="Entrez votre nom et prenom" class="form-control">
                    @error('nom_complet') <span class="text-danger">{{ $message }}</span> @enderror
                    
                    <br>
                    <h4>Adresse de livraison</h4>

                    <label for="pays">Pays *</label>
                    <select id="pays" name="pays" class="form-control" required>
                        <option value="">-- Choisir un pays de residence --</option>
                        @foreach($nations as $nation)
                            <option value="{{ $nation->idnation ?? $nation->id }}" {{ old('pays') == ($nation->idnation ?? $nation->id) ? 'selected' : '' }}>
                                {{ $nation->nomnation }}
                            </option>
                        @endforeach
                    </select>
                    @error('pays') <span class="text-danger">{{ $message }}</span> @enderror

                    <label for="adr">Adresse *</label>
                    <input type="text" name="adr" id="adr" value="{{ old('adr') }}" required placeholder="Entrez votre adresse" class="form-control">

                    <label for="adr_facultative">Appartement, suite, ect (facultatif)</label>
                    <input type="text" name="adr_facultative" id="adr_facultative" value="{{ old('adr_facultative') }}" class="form-control">

                    <div style="display: flex; gap: 20px;"> 
                        <div class="form-group">
                            <label for="cp">Code Postal *</label>
                            <input type="text" id="cp" name="cp" maxlength="5" value="{{ old('cp') }}" placeholder="Ex: 75001" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="ville">Ville *</label>
                            <select id="ville_select" name="ville">
                                <option value="">-- Remplissez le Code Postal d'abord --</option>
                            </select>
                            <input type="hidden" id="ville_real_name" name="ville_in" value="{{ old('ville') }}" class="form-control">
                        </div>
                    </div>

                    <label for="tel">Téléphone *</label>
                    <input type="tel" name="tel" id="tel" value="{{ old('tel') }}" required placeholder="+3363434343434" class="form-control">
                    @error('tel') <span class="text-danger">{{ $message }}</span> @enderror

                    <button type="button" class="btn-submit btn-next" data-next-step="livraison">SUITE</button>
                </div>

                {{-- Étape 2 : Livraison, Paiement et Facturation --}}
                <div id="livraison" class="formulaireLivraison hidden"> 
                    
                    <h3>2. Options de Livraison</h3>
                    <div class="delivery-option">
                        <label>
                            <span class="radio-custom"></span>
                            <input type="radio" name="delivery_method" value="1" {{ old('delivery_method', '1') == 1 ? 'checked' : '' }} required>
                            <div class="details">
                                <span>Standard (Jusqu'à 4 jours ouvrables)</span>
                            </div>
                            <span class="price">9,00 €</span>
                        </label>
                    </div>

                    <div class="delivery-option">
                        <label>
                            <span class="radio-custom"></span>
                            <input type="radio" name="delivery_method" value="2" {{ old('delivery_method') == 2 ? 'checked' : '' }} required>
                            <div class="details">
                                <span>Express (Jusqu'à 3 jours ouvrables)</span>
                            </div>
                            <span class="price">16,50 €</span>
                        </label>
                    </div>
                    
                    <br>
                    
                    <h3>3. Paiement</h3><br>
                    <p style="font-size: 14px; color: #555;">Toutes les transactions sont chiffrées et sécurisées.</p><br>

                    <div id="carteBancaire_saisie">
                        <div style="position: relative;">
                            <label for="card_number_saisie">Numéro de carte *</label>
                            <input type="text" class="form-control @error('card_number_saisie') is-invalid @enderror" id="card_number_saisie" 
                                name="card_number_saisie" placeholder="1234 5678 9012 3456" autocomplete="off" required>
                            @error('card_number_saisie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Dropdown cartes sauvegardées -->
                            <div id="saved-cards-dropdown" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1000;">
                                <ul id="saved-cards-list" 
                                    style="display:none; background: white; border: 1px solid #ccc; max-height: 150px; overflow-y: auto; margin: 0; padding: 0; list-style: none;">
                                    @foreach($savedCards as $card)
                                        <li class="saved-card-item" 
                                            data-card-number="{{ $card->refcb }}" 
                                            data-expiration="{{ $card->dateexpirationcb }}" 
                                            data-name="{{ $card->nomcb }}" 
                                            style="padding: 8px; cursor: pointer; border-bottom: 1px solid #eee;">
                                            {{ $card->nomcb }} - **** **** **** {{ substr($card->refcb, -4) }} (Exp: {{ $card->dateexpirationcb }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                                
                        <label for="card_name_saisie">Nom figurant sur la carte *</label>
                        <input type="text" class="form-control" id="card_name_saisie" value="{{ old('card_name_saisie') }}" name="card_name_saisie" required>
                                
                        <div style="display: flex; gap: 20px;">
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="cvv_saisie">CVV *</label>
                                <input type="text" class="form-control" id="cvv_saisie" placeholder="175" required>
                            </div>
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="expiry_date_saisie">Date d'expiration (MM/AA) *</label>
                                <input type="text" class="form-control" id="expiry_date_saisie" name="expiry_date_saisie" placeholder="MM/AA" required>
                            </div>
                        </div>



                        <input type="checkbox" name="save_cb" id="save_cb" value="1">
                        
                        <label for="save_cb">
                            Enregistrer les données bancaires pour les futures commandes
                        </label>                        
                    </div>

                    <br>
                    <h3>4. Facturation</h3>
                    <div class="delivery-option">
                        <label>
                            <span class="radio-custom"></span>
                            <input type="radio" name="billing_address" value="same" checked>
                            <div class="details">
                                <span>Même adresse que l'adresse de livraison</span>
                            </div>
                        </label>
                    </div>
                    <div class="delivery-option">
                        <label>
                            <span class="radio-custom"></span>
                            <input type="radio" name="billing_address" value="different">
                            <div class="details">
                                <span>Utiliser une adresse de facturation différente</span>
                            </div>
                        </label>
                    </div>                    
                    
                    <div style="display: flex; gap: 20px;">
                        <button type="button" class="btn-submit btn-prev" data-prev-step="coordonnees" style="background-color: #ccc; color: #333; flex: 1;">PRÉCÉDENT</button>

                        <button type="submit" id="finaliser_commande_btn" class="btn-submit" style="flex: 2;">
                            FINALISER LA COMMANDE
                            <div class="tooltip-container">
                                <div class="info-icon">i</div>
                                <div class="tooltip-box">
                                    Assurez-vous que toutes vos données sont correctes, vous ne pourrez plus accéder à votre panier actuel après avoir cliqué sur ce bouton.
                                </div>
                            </div>
                        </button>

                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
        
        {{-- Récapitulatif du Panier --}}
        <div class="container commande-form-box">
            <h2 style="text-align: center; color: #034f96;">
                Récapitulatif du panier
                <div class="tooltip-container">
                    <div class="info-icon">i</div>
                    <div class="tooltip-box">
                        Assurez-vous que tous vos articles apparaissent dans votre panier ci-dessous.
                    </div>
                </div>
            </h2>
            <hr>
            <div class="cart-items-container ">
                <h2>Articles du Panier</h2>
                

                <div class="cart-item-list"> 
                    @forelse ($contenirs as $contenir)                        
                        <?php
                            $compositeId = $contenir->idproduit . '-' . $contenir->idcoloris . '-' . $contenir->idtaille;
                        ?>

                        <div class="cart-item-row" data-ids="{{ $compositeId }}" 
                            style="display: flex; flex-direction: column; align-items: flex-start; padding: 10px 0;">
                                
                            <div class="item-details" style="display: flex; justify-content: space-between; align-items: flex-start; width: 100%;">
                                <img src="{{ asset($contenir->produit->photo->destinationphoto ?? 'path/to/default/image.png') }}" 
                                    style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px; border-radius: 4px;">

                                <p style="flex-grow: 1; margin: 0; font-size: 15px; padding-right: 10px;">
                                    {{ $contenir->produit->titreproduit ?? '' }}
                                </p>
                                
                                <span class="item-price" style="font-weight: bold; white-space: nowrap; font-size: 14px;">
                                    ({{ number_format($contenir->prixLigne, 2, ',', ' ') }} €)
                                </span>
                            </div>
                            <div class="quantity-control" style="display: flex; align-items: center;">
                                <input type="number" 
                                    class="quantity-input" 
                                    data-ids="{{ $compositeId }}" 
                                    value="{{ $contenir->qteproduit }}" 
                                    min="1" 
                                    style="width: 40px; text-align: center; margin: 0 5px;"
                                    readonly>
                            </div>
                            
                        </div>
                        <hr style="margin: 5px 0;">
                    @empty
                        <p>Votre panier est vide.</p>
                    @endforelse
                </div>
                <div class="cart-footer">
                    <div class="total-row">
                        <span>Total panier</span>
                        <span>{{ number_format($totalPanier, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="total-row">
                        <span>Total avec prix livraison</span>
                        <span id="total-price">{{ number_format($totalPanier, 2, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    window.phoneCodes = @json($nations->mapWithKeys(function($nation) {
        return [($nation->idnation ?? $nation->id) => $nation->codetel ?? ''];
    }));

    document.addEventListener('DOMContentLoaded', function() {
        let totalPanier = parseFloat("{{ number_format($totalPanier, 2, '.', '') }}");

        const deliveryPrices = {
            1: 9.00,
            2: 16.50
        };

        const totalPriceElement = document.getElementById('total-price');

        function updateTotal() {
            const selectedDelivery = document.querySelector('input[name="delivery_method"]:checked').value;
            const totalWithDelivery = totalPanier + deliveryPrices[selectedDelivery];
            totalPriceElement.textContent = totalWithDelivery.toFixed(2).replace('.', ',') + ' €';
        }

        const deliveryInputs = document.querySelectorAll('input[name="delivery_method"]');
        deliveryInputs.forEach(input => {
            input.addEventListener('change', updateTotal);
        });

        updateTotal();

        const cardInput = document.getElementById('card_number_saisie');
        const nameInput = document.getElementById('card_name_saisie');
        const expiryInput = document.getElementById('expiry_date_saisie');
        const dropdown = document.getElementById('saved-cards-list');

        if (!dropdown) return;

        cardInput.addEventListener('focus', function() {
            if (dropdown.children.length > 0) {
                dropdown.style.display = 'block';
            }
        });

        cardInput.addEventListener('blur', function() {
            setTimeout(() => {
                dropdown.style.display = 'none';
            }, 200);
        });

        dropdown.querySelectorAll('.saved-card-item').forEach(item => {
            item.addEventListener('click', function() {
                cardInput.value = this.dataset.cardNumber;
                nameInput.value = this.dataset.name;
                expiryInput.value = this.dataset.expiration;
                dropdown.style.display = 'none';

                const cvvInput = document.getElementById('cvv_saisie');
                if (cvvInput) {
                    cvvInput.value = '';
                }
            });
        });
    });
</script>

@endsection


