/* ==========================================
   1. VALIDATION DES FORMULAIRES
   ========================================== */
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

/* ==========================================
   2. API GOUV (CODE POSTAL / VILLE)
   ========================================== */
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
            lancerRechercheAPI(cpInput.value, villeHidden.value);
        }

        villeSelect.addEventListener('focus', function() {
            if (this.options.length <= 1 && cpInput.value.length === 5) {
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

/* ==========================================
   3. GESTION PRODUIT (STOCK, PRIX, COULEUR)
   ========================================== */
document.addEventListener('DOMContentLoaded', function() {
    const stockDisplay = document.getElementById('stock');
    const priceDisplay = document.querySelector('#price b');
    const colorSelect = document.getElementById('color');
    const sizeInputs = document.querySelectorAll('.size-input');
    const sizeLabels = document.querySelectorAll('.size-button');
    const addButton = document.getElementById('add-button');
    const stockData = window.stockData || {};

    // Sécurité si on n'est pas sur la page produit
    if (!colorSelect) return;

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

/* ==========================================
   4. SYSTEME DE VOTE
   ========================================== */
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

/* ==========================================
   5. GESTION DU PANIER (POPUP)
   ========================================== */
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

/* ==========================================
   6. AJAX CATEGORIES (PAGE CREATION)
   ========================================== */
document.addEventListener('DOMContentLoaded', function() {
    const selectCategorie = document.getElementById('idcategorie');
    const selectSousCat = document.getElementById('idsouscategorie');

    if (!selectCategorie || !selectSousCat) {
        return;
    }

    selectCategorie.addEventListener('change', function() {
        const idCat = this.value;

        selectSousCat.innerHTML = '<option value="">Chargement...</option>';
        selectSousCat.disabled = true;

        if (idCat) {
            fetch('/api/sous-categories/' + idCat)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau');
                    }
                    return response.json();
                })
                .then(data => {
                    selectSousCat.innerHTML = '<option value="">2. Choisir la sous-catégorie</option>';
                    
                    if (data.length > 0) {
                        data.forEach(sub => {
                            const option = document.createElement('option');
                            option.value = sub.idsouscategorie;
                            option.textContent = sub.nomsouscategorie;
                            selectSousCat.appendChild(option);
                        });
                        selectSousCat.disabled = false;
                    } else {
                        selectSousCat.innerHTML = '<option value="">Aucune sous-catégorie</option>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    selectSousCat.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        } else {
            selectSousCat.innerHTML = '<option value="">En attente de catégorie...</option>';
            selectSousCat.disabled = true;
        }
    });
});

/* ==========================================
   7. DETAILS DES COMMANDES (Voir/Masquer)
   ========================================== */
document.addEventListener('DOMContentLoaded', function() {
    

    const detailRows = document.querySelectorAll('.detail-row');
    if (detailRows.length > 0) {
        detailRows.forEach(row => {
            row.style.display = 'none';
        });
    }


    const toggleButtons = document.querySelectorAll('.js-toggle-order-details');

    if (toggleButtons.length > 0) {
        toggleButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                

                const orderId = this.getAttribute('data-id');
                const detailsRow = document.getElementById('details-' + orderId);
                
                if (detailsRow) {
                    if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                        detailsRow.style.display = 'table-row'; 
                        
                        if (this.classList.contains('btn-detail-outline')) {
                            this.textContent = 'Masquer le détail';
                        } else {
                            this.textContent = 'Masquer les articles';
                        }
                    } else {
                        detailsRow.style.display = 'none'; 
                        

                        if (this.classList.contains('btn-detail-outline')) {
                            this.textContent = 'Voir le détail';
                        } else {
                            this.textContent = 'Voir les articles';
                        }
                    }
                }
            });
        });
    }
});

/* ==========================================
   8. gestion du panier
   ========================================== */
document.addEventListener('DOMContentLoaded', function() {

    const cartPopup = document.getElementById('cart-popup');
    const cartOverlay = document.getElementById('cart-overlay');
    const closeBtn = document.getElementById('close-btn');
    const openCartBtn = document.getElementById('open-cart-btn');
    
    function openCart(event) {
        if (event) {
            event.preventDefault();
        }
        if (cartPopup) cartPopup.classList.add('is-open');
        if (cartOverlay) cartOverlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeCart() {
        if (cartPopup) cartPopup.classList.remove('is-open');
        if (cartOverlay) cartOverlay.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (openCartBtn) {
        openCartBtn.addEventListener('click', openCart);
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeCart);
    }
    
    if (cartOverlay) {
        cartOverlay.addEventListener('click', closeCart);
    }
});

function updateCartItem(compositeId, newQuantity) {            
    if (newQuantity < 1) {
        newQuantity = 1;
        const input = document.querySelector(`.quantity-input[data-ids="${compositeId}"]`);
        if (input) input.value = 1;
    }

    fetch(`${window.Laravel.panierUpdateQuantityUrl}/${compositeId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.Laravel.csrfToken
        },
        body: JSON.stringify({
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const itemRow = document.querySelector(`.quantity-input[data-ids="${compositeId}"]`)?.closest('.cart-item-row');
            if (itemRow) {
                const priceElem = itemRow.querySelector('.item-price');
                if (priceElem) priceElem.textContent = '(' + data.new_item_price + ' €)';
            }

            const totalElem = document.querySelector('.total-row span:last-child');
            if (totalElem) totalElem.textContent = data.new_total_price + ' €';

            console.log('Panier mis à jour avec succès.');
        } else {
            alert('Erreur lors de la mise à jour : ' + (data.message || ''));
        }
    })
    .catch(error => {
        console.error('Erreur réseau ou du serveur:', error);
        alert('Une erreur est survenue lors de la communication avec le serveur.');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.quantity-control button').forEach(button => {
        button.addEventListener('click', function() {
            const compositeId = this.getAttribute('data-ids'); 
            const input = document.querySelector(`.quantity-input[data-ids="${compositeId}"]`);
            if (!input) return;
            let currentValue = parseInt(input.value);
            
            if (this.classList.contains('increase-btn')) {
                currentValue++;
            } else if (this.classList.contains('decrease-btn') && currentValue > 1) {
                currentValue--;
            }
            
            input.value = currentValue;
            updateCartItem(compositeId, currentValue);
        });
    });
});

function removeCartItem(compositeId) {
    const url = `${window.Laravel.panierRemoveItemUrl}/${compositeId}`;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Erreur HTTP ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const itemRow = document.querySelector(`.cart-item-row[data-ids="${data.removed_composite_id}"]`);
            if (itemRow) {
                itemRow.remove();
            }

            const totalElem = document.querySelector('.total-row span:last-child');
            if (totalElem) totalElem.textContent = data.new_total_price + ' €';

            console.log('Produit supprimé avec succès.');
        } else {
            alert('Erreur lors de la suppression : ' + (data.message || ''));
        }
    })
    .catch(error => {
        console.error('Erreur réseau ou du serveur:', error);
        alert('Une erreur est survenue lors de la communication avec le serveur.');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            const compositeId = this.getAttribute('data-ids');
            
            if (confirm("Êtes-vous sûr de vouloir supprimer cet article du panier ?")) {
                removeCartItem(compositeId);
            }
        });
    });
});

