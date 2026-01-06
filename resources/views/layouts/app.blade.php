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
                    <a href="{{ route('commander.index') }}" class="checkout-btn" style="text-decoration: none; display: block; text-align: center;">
                        RÉGLER VOS ACHATS
                    </a>
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

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            panierUpdateQuantityUrl: '{{ url("panier/update-quantity") }}',
            panierRemoveItemUrl: '{{ url("panier") }}'
        };
    </script>

    <script src="{{ asset('js/main.js') }}?v={{ time() }}"></script>
    
    @yield('scripts')
</body>
</html>