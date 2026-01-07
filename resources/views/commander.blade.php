@extends('layouts.app')

@section('content')

<div class="commande-page-container">
    <div class="form-column">
        <div class="container commande-form-box">
            <h2 style="text-align: center; color: #034f96;">Commande</h2>
            <hr>
            
            <form id="commande-form" method="POST" action="{{ route('payer.processPayment', 'payer.store') }}">    
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
                    <label for="adr">Adresse *</label>
                    <input type="text" name="adr" id="adr" value="{{ old('adr') }}" required placeholder="Entrez votre adresse" class="form-control">

                    <label for="adr_facultative">Appartement, suite, ect (facultatif)</label>
                    <input type="text" name="adr_facultative" id="adr_facultative" value="{{ old('adr_facultative') }}" class="form-control">

                    <div style="display: flex; gap: 20px;"> 
                        <div class="form-group" style="flex: 1;"> 
                            <label for="cpostal">Code postal *</label>
                            <input type="text" name="cpostal" id="cpostal" value="{{ old('cpostal') }}" required class="form-control">
                            @error('cpostal') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="ville">Ville *</label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville') }}" required class="form-control">
                            @error('ville') <span class="text-danger">{{ $message }}</span> @enderror
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

                    <div style="display: none;">
                        <input type="hidden" id="card_number_hidden" name="card_number">
                        <input type="hidden" id="card_name_hidden" name="card_name">
                        <input type="hidden" id="expiry_date_hidden" name="expiry_date">
                    </div>

                    <div id="carteBancaire_saisie">
                        <label for="card_number_saisie">Numéro de carte *</label>
                        <input type="text" class="form-control" id="card_number_saisie" required>
                                
                        <label for="card_name_saisie">Nom figurant sur la carte *</label>
                        <input type="text" class="form-control" id="card_name_saisie" required>
                                
                        <div style="display: flex; gap: 20px;">
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="cvv_saisie">CVV *</label>
                                <input type="text" class="form-control" id="cvv_saisie" required>
                            </div>
                            <div class="form-group" style="flex-grow: 1;">
                                <label for="expiry_date_saisie">Date d'expiration (MM/AA) *</label>
                                <input type="text" class="form-control" id="expiry_date_saisie" placeholder="MM/AA" required>
                            </div>
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
                    
                    <div style="display: flex; gap: 20px;">
                        <button type="button" class="btn-submit btn-prev" data-prev-step="coordonnees" style="background-color: #ccc; color: #333; flex: 1;">PRÉCÉDENT</button>
                        <button type="button" id="finaliser_commande_btn" class="btn-submit" style="flex: 2;">FINALISER LA COMMANDE</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Logique pour le bouton "SUITE"
        const nextButton = document.querySelector('.btn-next');
        if (nextButton) {
            nextButton.addEventListener('click', function (e) {
                // Empêcher l'envoi du formulaire si des champs requis de la première étape sont vides
                const coordonneesForm = document.getElementById('coordonnees');
                const requiredInputs = coordonneesForm.querySelectorAll('[required]');
                let allValid = true;

                requiredInputs.forEach(input => {
                    if (!input.value) {
                        allValid = false;
                        // Vous pouvez ajouter ici une logique de validation visuelle (par exemple, bordure rouge)
                    }
                });

                if (allValid) {
                    const nextStepId = this.getAttribute('data-next-step'); // 'livraison'
                    const currentStep = this.closest('.formulaireLivraison'); // '#coordonnees'
                    const nextStep = document.getElementById(nextStepId); // '#livraison'

                    if (currentStep && nextStep) {
                        currentStep.classList.remove('active');
                        currentStep.classList.add('hidden');
                        nextStep.classList.remove('hidden');
                        nextStep.classList.add('active');
                    }
                } else {
                    alert('Veuillez remplir tous les champs obligatoires.'); // Afficher une alerte ou un message d'erreur
                }
            });
        }

        // Logique pour le bouton "PRÉCÉDENT"
        const prevButton = document.querySelector('.btn-prev');
        if (prevButton) {
            prevButton.addEventListener('click', function () {
                const prevStepId = this.getAttribute('data-prev-step'); // 'coordonnees'
                const currentStep = this.closest('.formulaireLivraison'); // '#livraison'
                const prevStep = document.getElementById(prevStepId); // '#coordonnees'

                if (currentStep && prevStep) {
                    currentStep.classList.remove('active');
                    currentStep.classList.add('hidden');
                    prevStep.classList.remove('hidden');
                    prevStep.classList.add('active');
                }
            });
        }
        const finaliserBtn = document.getElementById('finaliser_commande_btn');
        const carteForm = document.getElementById('cartebancaire-form');
        const commandeForm = document.getElementById('commande-form');

        if (finaliserBtn) {
            finaliserBtn.addEventListener('click', function (e) {
                e.preventDefault(); 
                
                const cardNumber = document.getElementById('card_number_saisie').value;
                const cardName = document.getElementById('card_name_saisie').value;
                const expiryDate = document.getElementById('expiry_date_saisie').value;

                document.getElementById('card_number_hidden').value = cardNumber;
                document.getElementById('card_name_hidden').value = cardName;
                document.getElementById('expiry_date_hidden').value = expiryDate;

                commandeForm.submit();
            });
        }
    });
</script>
@endsection