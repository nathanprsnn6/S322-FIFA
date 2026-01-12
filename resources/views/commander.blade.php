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
                    <input type="tel" name="tel" id="tel" value="{{ old('tel') }}" required placeholder="+33 6 34 34 34 34" class="form-control">
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
                    
                    <h3>3. Paiement</h3>
                    <p style="font-size: 14px; color: #555;">Toutes les transactions sont chiffrées et sécurisées.</p>

                    {{-- **Champs de carte bancaire modifiés : les attributs 'name' sont ajoutés directement** --}}
                    <div id="carteBancaire_saisie">
                        <label for="card_number_saisie">Numéro de carte *</label>
                        <input type="text" class="form-control" id="card_number_saisie" name="card_number_saisie" placeholder="1234 5678 9012 3456"required>
                                
                        <label for="card_name_saisie">Nom figurant sur la carte *</label>
                        <input type="text" class="form-control" id="card_name_saisie" name="card_name_saisie" required>
                                
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
                        <span>Total</span>
                        <span>{{ number_format($totalPanier, 2, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {        
        // --- 1. Logique Navigation Étapes (SUITE/PRÉCÉDENT) ---
        
        const nextButton = document.querySelector('.btn-next');
        if (nextButton) {
            nextButton.addEventListener('click', function (e) {
                const coordonneesForm = document.getElementById('coordonnees');
                const requiredInputs = coordonneesForm.querySelectorAll('[required]');
                let allValid = true;

                requiredInputs.forEach(input => {
                    if (!input.value) {
                        allValid = false;
                    }
                });

                if (allValid) {
                    const nextStepId = this.getAttribute('data-next-step');
                    const currentStep = this.closest('.formulaireLivraison');
                    const nextStep = document.getElementById(nextStepId);

                    if (currentStep && nextStep) {
                        currentStep.classList.remove('active');
                        currentStep.classList.add('hidden');
                        nextStep.classList.remove('hidden');
                        nextStep.classList.add('active');
                    }
                } else {
                    alert('Veuillez remplir tous les champs obligatoires.'); 
                }
            });
        }

        const prevButton = document.querySelector('.btn-prev');
        if (prevButton) {
            prevButton.addEventListener('click', function () {
                const prevStepId = this.getAttribute('data-prev-step');
                const currentStep = this.closest('.formulaireLivraison');
                const prevStep = document.getElementById(prevStepId);

                if (currentStep && prevStep) {
                    currentStep.classList.remove('active');
                    currentStep.classList.add('hidden');
                    prevStep.classList.remove('hidden');
                    prevStep.classList.add('active');
                }
            });
        }
        
        // --- 2. Logique Soumission du Formulaire (FINALISER LA COMMANDE) ---

        const finaliserBtn = document.getElementById('finaliser_commande_btn');
        const commandeForm = document.getElementById('commande-form'); 

    if (finaliserBtn) {
        finaliserBtn.addEventListener('click', function (e) {
            e.preventDefault(); 
            
            // Récupère les données de la carte saisies pour la validation JS
            const cardNumberInput = document.getElementById('card_number_saisie');
            const cardNameInput = document.getElementById('card_name_saisie');
            const expiryDateInput = document.getElementById('expiry_date_saisie');
            const cvvInput = document.getElementById('cvv_saisie');
            
            // Valide les champs de paiement (ceci est la validation client)
            if (!cardNumberInput.value || !cardNameInput.value || !expiryDateInput.value || !cvvInput.value) {
                alert('Veuillez saisir toutes les informations de paiement.');
                return;
            }
            
            commandeForm.submit();
        });
        }
    });


    
    document.addEventListener('DOMContentLoaded', function () {
        const paysSelect = document.getElementById('pays');
        const telInput = document.getElementById('tel');

        // Générer l'objet phoneCodes depuis PHP en JSON
        const phoneCodes = @json($nations->mapWithKeys(function($nation) {
            return [($nation->idnation ?? $nation->id) => $nation->codetel ?? ''];
        }));

        function updatePhonePrefix() {
            const selectedCountry = paysSelect.value;
            const prefix = phoneCodes[selectedCountry] || '';

            if (!prefix) {
                return;
            }

            if (!telInput.value.startsWith(prefix)) {
                const valueSansPrefix = telInput.value.replace(/^\+\d+/, '');
                telInput.value = prefix + valueSansPrefix;
            }
        }

        telInput.addEventListener('input', () => {
            const selectedCountry = paysSelect.value;
            const prefix = phoneCodes[selectedCountry] || '';

            if (!prefix) return;

            if (!telInput.value.startsWith(prefix)) {
                const valueSansPrefix = telInput.value.replace(/^\+\d+/, '');
                telInput.value = prefix + valueSansPrefix;
            }
        });

        paysSelect.addEventListener('change', () => {
            updatePhonePrefix();
            telInput.focus();
            setTimeout(() => {
                telInput.selectionStart = telInput.selectionEnd = telInput.value.length;
            }, 0);
        });

        updatePhonePrefix();
    });

    document.addEventListener('DOMContentLoaded', (event) => {
    // 1. Ciblez l'élément input par son ID ou son nom/type
    const telInput = document.getElementById('tel');

    // Assurez-vous que l'élément existe avant d'ajouter l'écouteur
    if (telInput) {
        telInput.addEventListener('input', function(e) {
            // Récupère la valeur actuelle et supprime tous les caractères non numériques
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';

            // Si la valeur commence par un code international (ex: +33)
            if (value.startsWith('33')) {
                // Ajout du préfixe international
                formattedValue += '+33';
                // La suite du numéro (on retire les 2 chiffres du 33)
                value = value.substring(2);

                // Formatage des paires de chiffres restantes (ex: 6 22 88 77 11)
                for (let i = 0; i < value.length; i += 2) {
                    if (i > 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value.substring(i, i + 2);
                }
            } else {
                // Si la valeur NE commence PAS par +33 (format local ou autre)
                // Appliquer un formatage simple par paires de chiffres
                for (let i = 0; i < value.length; i += 2) {
                    if (i > 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value.substring(i, i + 2);
                }
            }

            // Mettez à jour la valeur de l'input avec le formatage
            e.target.value = formattedValue.trim();
        });
    }
});
</script>
@endsection


