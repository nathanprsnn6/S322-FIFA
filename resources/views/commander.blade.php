@extends('layouts.app')

@section('content')

<div class="commande-page-container">
    <div class="form-column">
        <div class="container commande-form-box">
            <h2 style="text-align: center; color: #034f96;">Commande</h2>
            <hr>
            
            <form id="commande-form" method="POST" action="{{ route('commander.processPayment') }}">    
                @csrf

                <div id="coordonnees" class="formulaireLivraison active">
                    <h3>1. Informations de Contact et Adresse</h3>
                    
                    <label for="email">Courriel *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Entrez votre courriel" class="form-control">
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    
                    <label for="nom_complet">Nom complet *</label>
                    <input type="text" name="nom_complet" id="nom_complet" value="{{ old('nom_complet') }}" required placeholder="Entrez votre nom" class="form-control">
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
                    <input type="tel" name="tel" id="tel" value="{{ old('tel') }}" required placeholder="Entrez votre numéro de mobile" class="form-control">
                    @error('tel') <span class="text-danger">{{ $message }}</span> @enderror

                    <button type="button" class="btn-submit btn-next" data-next-step="livraison">SUITE</button>
                </div>

                <div id="livraison" class="formulaireLivraison hidden"> 
                    
                    <h3>2. Options de Livraison</h3>
                    <div class="delivery-option">
                        <label>
                            <span class="radio-custom"></span>
                            <input type="radio" name="delivery_method" value="standard" required checked>
                            <div class="details">
                                <span>Standard (Jusqu'à 4 jours ouvrables)</span>
                            </div>
                            <span class="price">9,00 €</span>
                        </label>
                    </div>

                    <div class="delivery-option">
                        <label>
                            <span class="radio-custom"></span>
                            <input type="radio" name="delivery_method" value="express" required>
                            <div class="details">
                                <span>Express (Jusqu'à 3 jours ouvrables)</span>
                            </div>
                            <span class="price">16,50 €</span>
                        </label>
                    </div>
                    
                    <br>
                    
                    
                    <h3>3. Paiement</h3>
                    <p style="font-size: 14px; color: #555;">Toutes les transactions sont chiffrées et sécurisées.</p>

                    <div id="carteBancaire">
                        <label for="card_number">Numéro de carte *</label>
                        <input type="text" class="form-control" id="card_number" name="card_number" required>
                                
                        <label for="card_name">Nom figurant sur la carte *</label>
                        <input type="text" class="form-control" id="card_name" name="card_name" required>
                                
                        <div style="display: flex; gap: 20px;">
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="cvv">CVV *</label>
                                <input type="text" class="form-control" id="cvv" name="cvv" required>
                            </div>
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="expiry_date">Date d'expiration (MM/AA) *</label>
                                <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/AA" required>
                            </div>
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
                        </div>
                    
                    <div style="display: flex; gap: 20px;">
                        <button type="button" class="btn-submit btn-prev" data-prev-step="coordonnees" style="background-color: #ccc; color: #333; flex: 1;">PRÉCÉDENT</button>
                        <button type="submit" class="btn-submit" style="flex: 2;">FINALISER LA COMMANDE</button>
                    </div>
                </div>

            </form>
        </div>
        <div class="container commande-form-box">
            <h2 style="text-align: center; color: #034f96;">Récapitulatif du panier</h2>
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
                        <span>Total</span>
                        <span>{{ number_format($totalPanier, 2, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        function validateStep(stepElement) {
            const requiredFields = stepElement.querySelectorAll('[required]');
            let formIsValid = true;
            let firstErrorField = null;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    formIsValid = false;                    
                    field.style.border = '1px solid red'; 
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                } else {
                    field.style.border = '1px solid #ddd';
                }
            });

            if (!formIsValid && firstErrorField) {
                 firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            return formIsValid;
        }

        const nextButtons = document.querySelectorAll('.btn-next');
        const prevButtons = document.querySelectorAll('.btn-prev');
        
        nextButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); 

                const currentStep = this.closest('.formulaireLivraison');
                
                if (!validateStep(currentStep)) {
                    alert('Veuillez remplir tous les champs obligatoires de l\'étape actuelle.');
                    return; 
                }

                const nextStepId = this.dataset.nextStep;
                const nextStep = document.getElementById(nextStepId);

                if (nextStep) {
                    currentStep.classList.remove('active');
                    currentStep.classList.add('hidden');
                    
                    nextStep.classList.remove('hidden');
                    nextStep.classList.add('active');
                    window.scrollTo(0, 0);
                }
            });
        });

        prevButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); 
                
                const currentStep = this.closest('.formulaireLivraison');
                const prevStepId = this.dataset.prevStep;
                const prevStep = document.getElementById(prevStepId);

                if (prevStep) {
                    currentStep.classList.remove('active');
                    currentStep.classList.add('hidden');
                    
                    prevStep.classList.remove('hidden');
                    prevStep.classList.add('active');
                    window.scrollTo(0, 0);
                }
            });
        });

        document.querySelectorAll('input[name="billing_address"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const billingAddressFields = document.getElementById('billing-address-fields');
                if (this.value === 'different') {
                    billingAddressFields.style.display = 'block';
                } else {
                    billingAddressFields.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection