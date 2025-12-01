@extends('layouts.app')
@section('content')<section id="p1" class="container">
        <div class="dots">
            <div class="dotN"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
        <p class="header-top">
            Vous avez déjà un compte ? <a href="#">&nbsp; Se connecter</a>
        </p>

        <h1>S'inscrire</h1>
        <h2>Étape 1 sur 3<br><b>Données personnelles</b></h2>

        <form method="post">
            <p class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom">
            </p>

            <div class="form-group">
                <label for="courriel">Courriel</label>
                <p class="hint-text">Voici votre identifiant FIFA</p>
                <input type="text" id="courriel" name="courriel">
            </div>

            <div class="form-group">
                <label for="jour_naissance">Date de naissance</label>
                <p class="date-selects">
                    <select id="jour_naissance" name="jour_naissance">
                        <option value="default" selected>Jour</option>
                    </select>
                    <select id="mois_naissance" name="mois_naissance" >
                        <option value="default" selected>Mois</option>
                    </select>
                    <select id="annee_naissance" name="annee_naissance">
                        <option value="default" selected>Année</option>
                    </select>
                </p>
            </div>

            <p class="form-group">
                <label for="pays_naissance">Pays de naissance</label>
                <select id="pays_naissance" name="pays_naissance">
                    <option value="default" selected></option>
                </select>
            </p>

            <p class="form-group">
                <label for="langue">Langue</label>
                <select id="langue" name="langue">
                    <option value="default" selected></option>
                </select>
            </p>

            <button id="btnP2" type="submit" class="button-primary">POURSUIVRE</button>
        </form>
    </section>

    <section id="p2" class="container">
        <div class="dots">
            <div class="dot"></div>
            <div class="dotN"></div>
            <div class="dot"></div>
        </div>
        <p class="header-top">
            Vous avez déjà un compte ? <a href="#">&nbsp; Se connecter</a>
        </p>

        <h1>S'inscrire</h1>
        <h2>Étape 2 sur 3<br><b>FIFA</b></h2>

        <form method="post">
            <div class="form-group">
                <label for="nickname">Surnom</label>
                <p class="hint-text">Les autres utilisateurs auront accès à cette information</p>
                <input type="text" id="nickname" name="nickname">
            </div>

            <p class="form-group">
                <label for="favorite">Favori</label>
                <input type="text" id="favorite" name="favorite">
            </p>

            <p class="hint-text">Pour en savoir plus sur, rendez-vous sur <a href="#"> le portail de protection des données de la FIFA</a> </p>

            <button id="btnP3" type="submit" class="button-primary">POURSUIVRE</button>
        </form>
    </section>

    <section id="p3" class="container">
        <div class="dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dotN"></div>
        </div>
        <div class="header-top">
            Vous avez déjà un compte ? <a href="#">&nbsp; Se connecter</a>
        </div>
        <div>
            <h1>S'inscrire</h1>
            <h2>Etape 3 sur 3<br><b>Choisir un mot de passe</b></h2><br>
            
            <form method="post">
                <div class="form-group">
                    <label for="choose_pwd">Choisir son mot de passe</label>
                    <p class="hint-text">Le mot de passe doit comprendre au moins 12 caractères, une minuscule, une majuscule, un caractère spécial, un chiffre</p>
                    <input type="password" id="choose_pwd" name="choose_pwd">
                </div>
    
                <p class="form-group">
                    <label for="conf_pwd">Confirmer votre mot de passe</label>
                    <input type="password" id="conf_pwd" name="conf_pwd">
                </p>
                <div id="chkx">
                    <input type="checkbox" id="checkbox">Je veux recevoir les dernières nouvelles et des annonces concernant des produits FIFA et de futurs évenements FIFA.<br><br>
                    <input type="checkbox" id="checkbox">Je veux recevoir des nouvelles occasionnelles et des offres spéciales de partenaires de la FIFA soigneusement choisis.<br><br>
                    <input type="checkbox" id="checkbox">J'ai lu et j'accepte les conditions d'utilisation.
                </div><br>
                <button id="btnP4" type="submit" class="button-primary">Créer un compte</button>
            </form>          
        </div>

    </section>

    <section id="p4" class="container">
        <center>
            <div>
                <h1>Vérifier votre boîte de réception</h1>
                <h2><b>Plus qu'un clic pour finaliser votre inscription.</b><br></h2>
                <h3>Vous n'avez pas reçu le courriel ? Vérifiez vos courriers indésirables.</h3>
                <br><br>
            </div>
            <div>
                <h1>Merci</h1>
                <h2><b>Vous êtes bien inscrit ! Vous pouvez fermer cette fenêtre.</b></h2>
            </div>
            <button id="btnP4" type="submit" class="button-primary">Fermer</button>
        </center>
    </section>

@endsection 
@section('scripts')
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection