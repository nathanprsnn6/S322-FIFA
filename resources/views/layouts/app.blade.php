<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FIFA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('img/FIFA.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>

<body>

<div id="cart-overlay" class="overlay"></div>
    <header class="fifa-header">
        
        <div class="header-left">
            <a href="{{ url('/') }}" class="logo-fifa">FIFA</a>
            <a href="{{ url('produits') }}" class="nav-link">Boutique</a>
            <a href="{{ url('voter') }}" class="nav-link">Voter</a>
            <a href="{{ url('publication') }}" class="nav-link">Publication</a>
            <a href="{{ url('faq') }}" class="nav-link">FAQ</a>
        </div>

        <div class="header-right">
            <a href="#" class="btn-auth" id="open-cart-btn">
                <span class="panier-icon"></span> <span id="panier-text">Panier</span>
            </a>

            @guest
                <a href="{{ route('login') }}" class="btn-auth">
                    <span class="user-icon"></span> Inscription / Connexion
                </a>
            @endguest

            @auth
                <div style="display: flex; align-items: center; gap: 15px;">
                    
                    @if(Auth::user()->idrole == 3)
                        <a href="{{ route('expedition.index') }}" class="btn-auth" style="background-color: #27ae60; color: white; border: none;">
                            <i class="fas fa-truck"></i> Espace Expédition
                        </a>
                    @endif

                    @if(Auth::user()->idrole == 5)
                        <a href="{{ route('vente.create') }}" class="btn-auth" style="background-color: #e67e22; color: white; border: none;">
                            <i class="fas fa-tags"></i> Service Vente
                        </a>
                    @endif

                    @if(Auth::user()->idrole == 7)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('siege.index') }}" 
                            style="color: #b91c1c; font-weight: bold; border: 2px solid #b91c1c; border-radius: 30px; padding: 5px 15px; margin-left: 10px;">
                                <i class="fas fa-building"></i> Espace Siège
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->idrole == 6)
                        <a href="{{ route('produitService.sans_prix') }}" class="btn-auth btn-management">
                            <i class="fas fa-tags"></i> Gestion Prix
                        </a>
                    @endif

                    <div class="user-dropdown">
                        <a href="#" class="btn-auth" style="background-color: white; color: #034f96;">
                            <span class="user-icon" style="border-color: #034f96;"></span> 
                            {{ Auth::user()->prenom ?? 'Mon Compte' }}
                        </a>

                        <div class="dropdown-menu">
                            <a href="{{ route('user.edit') }}">Mes informations</a>
                            <a href="{{ route('commandes.index') }}">Mes commandes</a>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 12px; font-weight: bold; text-decoration: underline;">
                            DÉCONNEXION
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        <div id="cart-popup" class="sidebar-cart">
            <div class="cart-header">
                <h3>MON PANIER</h3>
                <button id="close-btn" >&times;</button>
            </div>

            <div class="cart-items-container ">
                <h2 style="display: flex; align-items: center;">
                    Articles du Panier
                    <div class="tooltip-container">
                        <div class="info-icon">i</div>
                        <div class="tooltip-box">
                            Vous pouvez modifier la quantité ou supprimer un article.
                        </div>
                    </div>
                </h2>
    
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
                            
                            <div class="item-actions" style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-top: 5px;">
                                
                                <div class="quantity-control" style="display: flex; align-items: center;">
                                    <button class="quantity-btn decrease-btn" data-ids="{{ $compositeId }}">-</button>
                                    <input type="number" 
                                        class="quantity-input" 
                                        data-ids="{{ $compositeId }}" 
                                        value="{{ $contenir->qteproduit }}" 
                                        min="1" 
                                        style="width: 40px; text-align: center; margin: 0 5px;"
                                        onchange="updateCartItem('{{ $compositeId }}', this.value)">
                                        
                                    <button class="quantity-btn increase-btn" data-ids="{{ $compositeId }}">+</button>
                                </div>
                                
                                <button class="remove-item-btn" 
                                        data-ids="{{ $compositeId }}"
                                        style="background: none; border: none; color: #c0392b; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 5px;">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                        <hr style="margin: 5px 0;">
                    @empty
                        <p>Votre panier est vide.</p>
                    @endforelse
                </div>
            </div>
            
            <div class="cart-footer">

                <div class="total-row">
                    <span>Total</span>
                    <span>{{ number_format($totalPanier, 2, ',', ' ') }} €</span>
                </div>
                @if($totalPanier > 0)
                    @if(Auth::check())
                        <a href="{{ route('commander.index') }}" class="checkout-btn" style="text-decoration: none; display: block; text-align: center;">
                            RÉGLER VOS ACHATS
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="checkout-btn" style="text-decoration: none; display: block; text-align: center;">
                            CONNECTEZ VOUS AVANT DE REGLER VOS ACHATS
                        </a>
                    @endif
                @else
                    <a href="javascript:void(0);" class="checkout-btn disabled" style="text-decoration: none; display: block; text-align: center; pointer-events: none; opacity: 0.5; cursor: default;">
                        AJOUTER D'ABORD UN ARTICLE A VOTRE PANIER
                    </a>
                @endif
            </div>
        </div>

    </header>

    <main>
        @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 10px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{{ asset('js/tarteaucitron/tarteaucitron.js') }}"></script>

    <script type="text/javascript">

        tarteaucitron.user.facebookpixelId = '123456789';
        tarteaucitron.user.googleadsId = 'AW-123456789';

        tarteaucitron.init({
            "privacyUrl": "", /* URL de votre page de politique de confidentialité */
            "hashtag": "#tarteaucitron", /* Ouvrir le panneau via ce hashtag */
            "cookieName": "tarteaucitron", /* Nom du cookie déposé */
            "orientation": "bottom", /* Position de la bannière (top ou bottom) */
            "groupServices": true, /* Grouper les services par catégorie */
            "showAlertBanner": true, /* Afficher la petite bannière en bas à droite */
            "cookieslist": true, /* Afficher la liste des cookies installés */
            "closePopup": false, /* Fermer la popup après un clic */
            "showIcon": true, /* Afficher l'icône pour ouvrir le panneau */
            "iconPosition": "BottomRight", /* Position de l'icône */
            "adblocker": false, /* Afficher un message si un adblocker est détecté */
            "DenyAllCta" : true, /* Afficher le bouton "Tout refuser" */
            "AcceptAllCta" : true, /* Afficher le bouton "Tout accepter" */
            "highPrivacy": true, /* Désactiver le consentement automatique au scroll */
            "handleBrowserRequests": true, /* Gérer les requêtes de l'utilisateur via le navigateur */
        });

       (tarteaucitron.job = tarteaucitron.job || []).push('facebookpixel');
        (tarteaucitron.job = tarteaucitron.job || []).push('googleads');

        (tarteaucitron.job = tarteaucitron.job || []).push('gtag');

    </script>
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            panierUpdateQuantityUrl: '{{ url("panier/update-quantity") }}',
            panierRemoveItemUrl: '{{ url("panier") }}'
        };
    </script>

    <script src="{{ asset('js/main.js') }}?v={{ time() }}"></script>

    <script>
        var botmanWidget = {
            aboutText: 'Assistant FIFA',
            introMessage: "Bonjour ! Je peux vous aider avec vos votes, le suivi de commande ou les infos produits.",
            title: "Support FIFA",
            mainColor: "#034f96",
            bubbleBackground: "#034f96", 
            headerTextColor: "#fff",
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
    
    <script>
        function changeLanguage(lang) {
            // 1. On vérifie si l'utilisateur a accepté le service "preferences" dans Tarteaucitron
            if (tarteaucitron.state.preferences === true) {
                // 2. On enregistre le cookie pour 1 an
                document.cookie = "app_locale=" + lang + ";path=/;max-age=" + (365*24*60*60);
                // 3. On recharge la page pour que le Middleware Laravel lise le cookie
                window.location.reload();
            } else {
                alert("Vous devez accepter les cookies de 'Préférences' pour changer la langue durablement.");
                tarteaucitron.userInterface.openPanel(); // On ouvre le panneau pour l'aider
            }
        }

        //Fusau horaire
        if (tarteaucitron.state.preferences === true) {
            const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
            document.cookie = "app_timezone=" + tz + ";path=/;max-age=31536000";
        }
</script>
    @yield('scripts')    
</body>

</html>