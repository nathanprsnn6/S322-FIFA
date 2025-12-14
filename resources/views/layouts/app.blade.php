<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    
                    {{-- 
                        DETECTION DU ROLE : SERVICE EXPEDITION 
                    --}}
                    @if(Auth::user()->idrole == 3)
                        <a href="{{ route('expedition.index') }}" class="btn-auth" style="background-color: #27ae60; color: white; border: none;">
                            <i class="fas fa-truck"></i> Espace Expédition
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
                <div class="cart-item">
                    @isset($contenirs)
                        @forelse ($contenirs as $contenir)
                            <p>Panier n°: <strong>{{ $contenir->idpanier }}</strong></p>
                            <p>Produit n°: <strong>{{ $contenir->idproduit }}</strong></p>
                            <p>Quantité: <strong>{{ $contenir->qteproduit }}</strong></p>
                            <hr>
                        @empty
                            <p>Votre panier est vide.</p>
                        @endforelse
                    @else
                        <p>Les données du panier n'ont pas été chargées.</p>
                    @endisset
                </div>
            </div>
            
            <div class="cart-footer">
                @php
                    $totalPanier = 0;
                    if (isset($paniers) && is_iterable($paniers)) {
                        foreach ($paniers as $panier) {
                            $totalPanier += (float) $panier->prixpanier;
                        }
                    }
                @endphp

                <div class="total-row">
                    <span>Total</span>
                    <span>{{ number_format($totalPanier, 2, ',', ' ') }} €</span>
                </div>
                <button class="checkout-btn">RÉGLER VOS ACHATS</button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const cartPopup = document.getElementById('cart-popup');
            const cartOverlay = document.getElementById('cart-overlay');
            const closeBtn = document.getElementById('close-btn');
            const openCartBtn = document.getElementById('open-cart-btn');
            
            function openCart(event) {
                if (event) { event.preventDefault(); }
                cartPopup.classList.add('is-open');
                cartOverlay.classList.add('is-open');
                document.body.style.overflow = 'hidden';
            }

            function closeCart() {
                cartPopup.classList.remove('is-open');
                cartOverlay.classList.remove('is-open');
                document.body.style.overflow = '';
            }

            if (openCartBtn) openCartBtn.addEventListener('click', openCart);
            if (closeBtn) closeBtn.addEventListener('click', closeCart);
            if (cartOverlay) cartOverlay.addEventListener('click', closeCart);
        });
    </script>
    @yield('scripts')

</body>
</html>