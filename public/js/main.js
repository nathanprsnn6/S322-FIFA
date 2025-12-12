/* =========================================
   1. FONCTIONS DE VALIDATION (Pas de changement)
   ========================================= */
   function checkFields1() {
    const fields = document.querySelectorAll('#nom,#jour_naissance, #mois_naissance, #annee_naissance, #pays_naissance, #langue, #prenom, #courriel');
    const button = document.getElementById('submitButton1');
    let allFilled = true;
    if(fields && button) {
        fields.forEach(field => { if (field.value === '') allFilled = false; });
        button.disabled = !allFilled;
    }
}
function checkFields2() {
    const fields = document.querySelectorAll('#nickname, #favorite');
    const button = document.getElementById('submitButton2');
    let allFilled = true;
    if(fields && button) {
        fields.forEach(field => { if (field.value === '') allFilled = false; });
        button.disabled = !allFilled;
    }
}
function checkFields3() {
    const fields = document.querySelectorAll('#choose_pwd, #conf_pwd');
    const checkbox = document.getElementById('checkbox3');
    const button = document.getElementById('submitButton3');
    let allFilled = true;
    if(fields && button && checkbox) {
        fields.forEach(field => { if (field.value === '') allFilled = false; });
        if (!checkbox.checked) allFilled = false;
        button.disabled = !allFilled;
    }
}
// Ajoute tes autres checks ici si besoin...
//========================================= LOGIQUE VILLE / CODE POSTAL (Modifié)
document.addEventListener('DOMContentLoaded', function() {
    const cpInput = document.getElementById('cp');
    const villeSelect = document.getElementById('ville_select');
    const villeHidden = document.getElementById('ville_real_name');

    if (cpInput && villeSelect && villeHidden) {

        // Fonction pour charger les villes via l'API
        function lancerRechercheAPI(cp, villeActuelle) {
            // On désactive le select visuellement le temps de charger pour montrer qu'il se passe un truc
            villeSelect.style.opacity = "0.5"; 

            const url = `https://geo.api.gouv.fr/communes?codePostal=${cp}&fields=nom&format=json&geometry=centre`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    villeSelect.style.opacity = "1"; // On réactive
                    villeSelect.innerHTML = ''; // On vide la liste (on supprime la ville unique de Laravel)
                    
                    if (data.length > 0) {
                        data.forEach(commune => {
                            let option = document.createElement('option');
                            option.value = commune.nom;
                            option.text = commune.nom;
                            
                            // Si c'est la ville enregistrée, on la sélectionne
                            if (villeActuelle && commune.nom === villeActuelle) {
                                option.selected = true;
                            }
                            villeSelect.appendChild(option);
                        });
                        
                        // Sécurité : Si la ville actuelle n'est pas dans l'API, on la remet
                        if (villeActuelle && !Array.from(villeSelect.options).some(o => o.value === villeActuelle)) {
                            let opt = new Option(villeActuelle, villeActuelle, true, true);
                            villeSelect.add(opt);
                        }
                    } else {
                        villeSelect.innerHTML = '<option value="">Code Postal inconnu</option>';
                    }
                })
                .catch(err => {
                    console.error("Erreur API", err);
                    villeSelect.style.opacity = "1";
                });
        }

        // 1. AU DÉMARRAGE : On lance la recherche directe
        if (cpInput.value.length === 5) {
            console.log("Lancement auto API...");
            lancerRechercheAPI(cpInput.value, villeHidden.value);
        }

        // 2. SÉCURITÉ : Si on clique sur la liste et qu'il n'y a qu'1 seule option (celle de Laravel), on relance !
        villeSelect.addEventListener('focus', function() {
            if (this.options.length <= 1 && cpInput.value.length === 5) {
                console.log("Relance API au clic...");
                lancerRechercheAPI(cpInput.value, villeHidden.value);
            }
        });

        // 3. Changement de CP manuel
        cpInput.addEventListener('input', function() {
            if (this.value.length === 5) {
                villeHidden.value = ""; 
                lancerRechercheAPI(this.value, null);
            }
        });

        // 4. Sélection d'une ville
        villeSelect.addEventListener('change', function() {
            villeHidden.value = this.value;
        });
    }
});


const stockDisplay = document.querySelector('#stock')

document.querySelectorAll('.size-button').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.size-button').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        const selectedSize = this.dataset.id;
        stockDisplay.textContent = "Stock restant : " + '5'
    });
});

const colorSelect = document.getElementById('color');
const priceDisplay = document.querySelector('#price b');


colorSelect.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const newPrice = parseFloat(selectedOption.getAttribute('data-price'));
    priceDisplay.textContent = newPrice.toFixed(2) + ' €';
});


/* =========================================
   4. GESTION DES VOTES (Version Strict)
   ========================================= */
   document.addEventListener('DOMContentLoaded', function() {
    // On récupère tous les boutons radio de vote
    const radios = document.querySelectorAll('.vote-radio');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            // L'ID du joueur qu'on vient de cliquer
            const playerId = this.dataset.player;
            // Le rang qu'on vient de choisir (rank_1, rank_2 ou rank_3)
            const currentRankName = this.name;

            // Si on coche une case, on doit décocher ce même joueur 
            // s'il était sélectionné ailleurs (sur une autre ligne de rang)
            radios.forEach(otherRadio => {
                // Si c'est le même joueur MAIS un rang différent
                if (otherRadio.dataset.player === playerId && otherRadio.name !== currentRankName) {
                    otherRadio.checked = false;
                }
            });
        });
    });
});
