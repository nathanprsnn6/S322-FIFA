@extends('layouts.app')

@section('content')

<div class="commande-page-container">
    <div class="form-column">
        <div class="container commande-form-box">
            <h2 style="text-align: center; color: #034f96;">Commande</h2>
            <hr>
            <form method="POST" action="{{ route('commander.processPayment') }}">    
                @csrf

                <div id="coordonnees" class="formulaireLivraison active">
                    <div id="email">
                        <label for="email">Courriel *</label>
                        <input 
                            type="text" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="Entrez votre courriel"
                            class="form-control"
                        >
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div id="nom_complet">
                        <label for="nom_complet">Nom complet *</label>
                        <input 
                            type="text" 
                            name="nom_complet" 
                            id="nom_complet" 
                            value="{{ old('nom_complet') }}" 
                            required 
                            placeholder="Entrez votre nom"
                            class="form-control"
                        >

                        @error('nom_complet')            
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    

                    <br><br>

                    <div id="adr">
                        <p>Adresse de livraison</p>
                        <label for="adr">Adresse *</label>
                        <input 
                            type="text" 
                            name="adr" 
                            id="adr" 
                            value="{{ old('adr') }}" 
                            required 
                            placeholder="Entrez votre adresse"
                            class="form-control"
                        >
                    </div>            

                        <div id="adr_facultative">
                            <p>Appartement, suite, ect (champ facultatif)</p>
                            <label for="adr_facultative"></label>
                            <input
                            type="text" 
                                name="adr_facultative" 
                                id="adr_facultative" 
                                value="{{ old('adr_facultative') }}" 
                                placeholder=""
                                class="form-control"
                            >
                        </div>

                        <div style="display: flex; gap: 20px;"> <div class="form-group" style="flex: 1;"> <label for="cpostal">Code postal *</label>
                                <input type="text" name="cpostal" id="cpostal" value="{{ old('cpostal') }}" class="form-control">
                                @error('cpostal')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label for="ville">Ville *</label>
                                <input type="text" name="ville" id="ville" value="{{ old('ville') }}" class="form-control">
                                @error('ville')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <p>Téléphone *</p>
                            <label for="tel"></label>
                            <input
                            type="text" 
                                name="tel" 
                                id="tel" 
                                value="{{ old('tel') }}" 
                                placeholder="Entrez votre numéro de mobile"
                                class="form-control"
                            >
                        </div>

                        <button type="submit" class="btn-submit">SUITE</button>
                    </div>

                    <div id="livraison" class="formulaireLivraison hidden"> 
                        <h3>Livraison</h3>

                        <div id="carteBancaire" class="container" style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
                            <h2 style="text-align: center; color: #034f96;">Paiement Sécurisé</h2>
                            <hr>
                            
                                <form method="POST" action="{{ route('produits.index') }}">
                                @csrf

                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label for="card_number" style="display: block; font-weight: bold; margin-bottom: 5px;">Numéro de carte</label>
                                    <input type="text" class="form-control" id="card_number" name="card_number" required
                                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                </div>
                                
                                <div style="display: flex; gap: 20px;">
                                    <div class="form-group" style="margin-bottom: 15px; flex-grow: 2;">
                                        <label for="expiry_date" style="display: block; font-weight: bold; margin-bottom: 5px;">Date d'expiration (MM/AA)</label>
                                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/AA" required
                                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 15px; flex-grow: 1;">
                                        <label for="cvv" style="display: block; font-weight: bold; margin-bottom: 5px;">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" required
                                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary"
                                        style="background-color: #034f96; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; margin-top: 15px;">
                                    Payer
                                </button>
                            </form>
                        </div>

                    <h3>PAIEMENT</h3>
                    <button type="submit" class="btn-submit">FINALISER LA COMMANDE</button>
                </div>

                <button type="submit" class="btn btn-primary"
                    style="background-color: #034f96; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; margin-top: 15px;">
                    Poursuivre
                </button>
                <button type="submit" class="btn btn-primary"
                    style="background-color: #034f96; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; margin-top: 15px;">
                    Précédent
                </button>
            </form>
        </div>
    </div>
</form>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    
        const nextButtons = document.querySelectorAll('.btn-next');
        const prevButtons = document.querySelectorAll('.btn-prev');

        const initialStep = document.querySelector('.formulaireLivraison.active'); 
        
        nextButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); 

                const etapeActive = this.closest('.formulaireLivraison');
                
                if (!etapeActive) {
                    console.error("Could not find the current step element with class '.formulaireLivraison'.");
                    return;
                }

                const requiredFields = etapeActive.querySelectorAll('[required]');
                let formIsValid = true;
                // You should implement actual validation logic here, e.g.,
                // for (const field of requiredFields) {
                //     if (!field.value.trim()) {
                //         formIsValid = false;
                //         field.classList.add('error'); // Example visual feedback
                //     } else {
                //         field.classList.remove('error');
                //     }
                // }

                // Assuming validation passes (or you remove the validation logic for testing)
                // if (!formIsValid) {
                //     alert('Please fill out all required fields.');
                //     return; // Stop if validation fails
                // }


                const nextStepId = this.dataset.nextStep;
                const nextStep = document.getElementById(nextStepId);

                if (nextStep) {
                    currentStep.classList.remove('active');
                    currentStep.classList.add('hidden');
                    
                    nextStep.classList.remove('hidden');
                    nextStep.classList.add('active');
                }
            });
        });

        prevButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); 
                
                const currentStep = this.closest('.formulaireLivraison');
                if (!currentStep) {
                    console.error("Could not find the current step element with class '.formulaireLivraison'.");
                    return;
                }

                const prevStepId = this.dataset.prevStep;
                const prevStep = document.getElementById(prevStepId);

                if (prevStep) {
                    currentStep.classList.remove('active');
                    currentStep.classList.add('hidden');
                    
                    prevStep.classList.remove('hidden');
                    prevStep.classList.add('active');
                }
            });
        });
    });
</script>
@endsection