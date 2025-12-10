@extends('layouts.app')

@section('content') 
    <section id="p3" class="container">
        <div class="dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dotN"></div>
        </div>
        <div class="header-top">
            Vous avez déjà un compte ? <a href="login">&nbsp; Se connecter</a>
        </div>
        

        @if ($errors->any())
            <div style="color: red; background: #ffcccc; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <h1>S'inscrire</h1>
            <h2>Etape 3 sur 3<br><b>Choisir un mot de passe</b></h2><br>
            
            <form action="{{ route('inscription3.store') }}" method="POST">
                @csrf {{-- Token de sécurité OBLIGATOIRE --}}

                <div class="form-group">
                    <label for="mdp">Choisir son mot de passe *</label>
                    <p class="hint-text">Le mot de passe doit comprendre au moins 8 caractères.</p>
                    {{-- ATTENTION : name="mdp" pour correspondre au contrôleur ($request->mdp) --}}
                    <input type="password" id="mdp" name="mdp" required>
                </div>
    
                <p class="form-group">
                    <label for="conf_pwd">Confirmer votre mot de passe *</label>
                    {{-- name="conf_pwd" pour la règle 'same:mdp' --}}
                    <input type="password" id="conf_pwd" name="conf_pwd" required>
                </p>

                <div id="chkx">
                    {{-- J'ai donné des noms distincts aux checkboxes --}}
                    <label style="display: block; margin-bottom: 10px;">
                        <input type="checkbox" name="news_fifa"> Je veux recevoir les dernières nouvelles et des annonces concernant des produits FIFA et de futurs évenements FIFA.
                    </label>

                    <label style="display: block; margin-bottom: 10px;">
                        <input type="checkbox" name="news_partners"> Je veux recevoir des nouvelles occasionnelles et des offres spéciales de partenaires de la FIFA soigneusement choisis.
                    </label>

                    <label style="display: block; margin-bottom: 10px;">
                        <input type="checkbox" name="cgu" required> J'ai lu et j'accepte les conditions d'utilisation. *
                    </label>
                </div><br>

                <button type="submit" class="button-primary btnP4" id="submitButton3" href="inscription4">CRÉER UN COMPTE</button>
            </form>          
        </div>

    </section>
@endsection 
@section('scripts')
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection