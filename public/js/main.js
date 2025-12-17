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



document.addEventListener('DOMContentLoaded', function() {
    const cpInput = document.getElementById('cp');
    const villeSelect = document.getElementById('ville_select');
    const villeHidden = document.getElementById('ville_real_name');

    if (cpInput && villeSelect && villeHidden) {

        function lancerRechercheAPI(cp, villeActuelle) {
            villeSelect.style.opacity = "0.5"; 

            const url = `https://geo.api.gouv.fr/communes?codePostal=${cp}&fields=nom&format=json&geometry=centre`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    villeSelect.style.opacity = "1";
                    villeSelect.innerHTML = '';
                    
                    if (data.length > 0) {
                        data.forEach(commune => {
                            let option = document.createElement('option');
                            option.value = commune.nom;
                            option.text = commune.nom;
                            
                            if (villeActuelle && commune.nom === villeActuelle) {
                                option.selected = true;
                            }
                            villeSelect.appendChild(option);
                        });
                        
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

        if (cpInput.value.length === 5) {
            console.log("Lancement auto API...");
            lancerRechercheAPI(cpInput.value, villeHidden.value);
        }

        villeSelect.addEventListener('focus', function() {
            if (this.options.length <= 1 && cpInput.value.length === 5) {
                console.log("Relance API au clic...");
                lancerRechercheAPI(cpInput.value, villeHidden.value);
            }
        });

        cpInput.addEventListener('input', function() {
            if (this.value.length === 5) {
                villeHidden.value = ""; 
                lancerRechercheAPI(this.value, null);
            }
        });

        villeSelect.addEventListener('change', function() {
            villeHidden.value = this.value;
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const stockDisplay = document.getElementById('stock');
    const priceDisplay = document.querySelector('#price b');
    const colorSelect = document.getElementById('color');
    const sizeInputs = document.querySelectorAll('.size-input');
    const sizeLabels = document.querySelectorAll('.size-button');
    const addButton = document.getElementById('add-button');
    const stockData = window.stockData || {};

    function updateProductInfo() {
        const selectedColorId = colorSelect.value;
        const selectedSizeInput = document.querySelector('input[name="size"]:checked');
        
        const selectedOption = colorSelect.options[colorSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        priceDisplay.textContent = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(price);

        if (selectedSizeInput) {
            const selectedSizeId = selectedSizeInput.value;
            
            let stockQty = 0;
            if (stockData[selectedColorId] && stockData[selectedColorId][selectedSizeId]) {
                stockQty = stockData[selectedColorId][selectedSizeId];
            }

            if (stockQty > 0) {
                stockDisplay.textContent = "Stock restant : " + stockQty;
                stockDisplay.style.color = "green";
                addButton.disabled = false;
                addButton.style.opacity = "1";
                addButton.style.cursor = "pointer";

                const quantityInput = document.getElementById('quantity');
                quantityInput.max = stockQty;

                if (parseInt(quantityInput.value) > stockQty) {
                    quantityInput.value = stockQty;
                }
            } else {
                stockDisplay.textContent = "Rupture de stock";
                stockDisplay.style.color = "red";
                addButton.disabled = true;
                addButton.style.opacity = "0.5";
                addButton.style.cursor = "not-allowed";

                const quantityInput = document.getElementById('quantity');
                quantityInput.max = 0;
                quantityInput.value = 0;
            }
        } else {
            stockDisplay.textContent = "Veuillez sélectionner une taille";
            stockDisplay.style.color = "#333";
            addButton.disabled = true;

            const quantityInput = document.getElementById('quantity');
            quantityInput.max = 1;
            quantityInput.value = 1;
        }
    }

    sizeInputs.forEach(input => {
        input.addEventListener('change', function() {
            sizeLabels.forEach(lbl => lbl.classList.remove('active'));
            const label = document.querySelector(`label[for="${this.id}"]`);
            if(label) label.classList.add('active');
            
            updateProductInfo();
        });
    });

    colorSelect.addEventListener('change', updateProductInfo);

    updateProductInfo();
});





document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('.vote-radio');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const playerId = this.dataset.player;
            const currentRankName = this.name;

            radios.forEach(otherRadio => {
                if (otherRadio.dataset.player === playerId && otherRadio.name !== currentRankName) {
                    otherRadio.checked = false;
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.js-toggle-order-details');
    buttons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = btn.getAttribute('data-id');
            const row = document.getElementById('details-' + id);
            if (row) {
                const isHidden = getComputedStyle(row).display === 'none';
                row.style.display = isHidden ? 'table-row' : 'none';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const cartPopup = document.getElementById('cart-popup');
    const cartOverlay = document.getElementById('cart-overlay');
    const closeBtn = document.getElementById('close-btn');
    const openCartBtn = document.getElementById('open-cart-btn');
    
    const openCart = (e) => {
        if (e) e.preventDefault();
        if (cartPopup) cartPopup.classList.add('is-open');
        if (cartOverlay) cartOverlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    };

    const closeCart = () => {
        if (cartPopup) cartPopup.classList.remove('is-open');
        if (cartOverlay) cartOverlay.classList.remove('is-open');
        document.body.style.overflow = '';
    };

    if (openCartBtn) openCartBtn.addEventListener('click', openCart);
    if (closeBtn) closeBtn.addEventListener('click', closeCart);
    if (cartOverlay) cartOverlay.addEventListener('click', closeCart);
});