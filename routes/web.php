<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PersonneTest;
use App\Http\Controllers\UtilisateurTest;
use App\Http\Controllers\ProduitTest;
use App\Http\Controllers\Inscription1;
use App\Http\Controllers\Inscription2;
use App\Http\Controllers\Inscription3;
use App\Http\Controllers\Inscription4;
use App\Http\Controllers\InscriptionPro;
use App\Http\Controllers\Modification;
use App\Http\Controllers\Connexion;
use App\Http\Controllers\ProduitDetail;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\VoterDetail;
use App\Http\Controllers\Commander;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\Payer;
use App\Http\Controllers\Commande;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\SiegeController;
use App\Http\Controllers\ProduitService;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicationDetail;
use App\Http\Controllers\Faq;
use App\Http\Controllers\BotManController; 
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\TypeVoteController;
use App\Http\Controllers\CommentaireController;

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']); 
 

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- LISTES & TESTS ---
Route::get('/personnes', [PersonneTest::class, 'index']);
Route::get('/utilisateurs', [UtilisateurTest::class, 'index']);

// --- PRODUITS ---
Route::get('/produits', [ProduitTest::class, 'index'])->name('produits.index');
Route::get('/produit/{id}', [ProduitDetail::class, 'show'])->name('produit.show');
Route::post('/produits', [ProduitDetail::class, 'store'])->name('produit.store');

// --- GESTION PRIX ---
Route::get('produitService', [ProduitService::class, 'produitsSansPrix'])->name('produitService.sans_prix');
Route::post('/produits/save-prix', [ProduitService::class, 'updatePrix'])->name('produits.save_prix');

// --- INSCRIPTION (Etapes) ---
Route::get('/inscription1', [Inscription1::class, 'index']); // Ancien lien ?
Route::get('/inscription/etape1', [Inscription1::class, 'index'])->name('inscription1.index');
Route::post('/inscription/etape1', [Inscription1::class, 'store'])->name('inscription1.store');
 
Route::get('/inscription/etape2', [Inscription2::class, 'index'])->name('inscription2.index');
Route::post('/inscription/etape2', [Inscription2::class, 'store'])->name('inscription2.store');
 
Route::get('/inscription/etape3', [Inscription3::class, 'index'])->name('inscription3.index');
Route::post('/inscription/etape3', [Inscription3::class, 'store'])->name('inscription3.store');
 
Route::get('/inscription/etape4', [Inscription4::class, 'index'])->name('inscription4.index');
 
 
// --- INSCRIPTION PRO ---
Route::get('/devenir-pro', [InscriptionPro::class, 'create'])->name('pro.create');
Route::post('/devenir-pro', [InscriptionPro::class, 'store'])->name('pro.store');


// --- AUTHENTIFICATION ---

// --- ROUTES DE CONNEXION CLASSIQUE & A2F ---
Route::get('/connexion', function () {
    return view('login'); // Ta page de login FIFA
})->name('login');

Route::get('/connexion/verification', function () {
    if(!session()->has('a2f_user_id')) return redirect('/connexion');
    return view('login-a2f'); // SANS "auth." car tu l'as mis à la racine
})->name('login.a2f.view');

Route::post('/connexion/verification', [Connexion::class, 'verifyA2f'])->name('login.a2f.verify');


// --- ROUTES MOT DE PASSE OUBLIÉ (FIFA STYLE) ---

// 1. Page pour saisir le mail
Route::get('forgotpassword', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

// 2. Envoi du mail
Route::post('forgotpassword', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// 3. Page de saisie du nouveau mot de passe (Lien cliqué dans Mailtrap)
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

// 4. Action de mise à jour en base
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

// Valider le code envoyé par le client
Route::post('/connexion/verification', [Connexion::class, 'verifyA2f'])->name('login.a2f.verify');
Route::get('/connexion', [Connexion::class, 'show'])->name('login');
Route::post('/connexion', [Connexion::class, 'login'])->name('login.submit');
Route::post('/logout', [Connexion::class, 'logout'])->name('logout');

// --- PROFIL ---
Route::get('/modifier', [Modification::class, 'edit'])->name('user.edit');
Route::put('/modifier', [Modification::class, 'update'])->name('user.update');

// --- VOTE ---
Route::get('/voter', [VoterController::class, 'index'])->name('voter.index');
Route::post('/voter', [VoterController::class, 'store'])->name('voter.store');
Route::get('/voter/{id}', [VoterDetail::class, 'show'])->name('voter.show');
Route::get('/verifier-vote/{idtypevote}', [VoterController::class, 'checkVote'])->name('verifier.vote');

// --- PANIER ---
Route::get('/panier', [PanierController::class, 'getCartItems'])->name('panier.getCartItems');
Route::put('/panier/update-quantity/{compositeId}', [PanierController::class, 'updateQuantity'])->name('panier.update_quantity');
Route::delete('/panier/{compositeId}', [PanierController::class, 'removeItem'])->name('panier.remove_item');

// --- PAYER & COMMANDE---
Route::get('/commander', [Commander::class, 'index'])->name('commander.index');

Route::post('/payer', [Payer::class, 'processPayment'])->name('payer.processPayment');

// --- PUBLICATIONS ---
Route::get('/publication', [PublicationController::class, 'index'])->name('publication.index');
Route::get('/publication/{id}', [PublicationDetail::class, 'show'])->name('publication.show');

// --- FAQ ---
Route::get('/faq', [Faq::class, 'index'])->name('faq.index');

// --- ROUTES PROTÉGÉES (AUTH) ---
Route::middleware(['auth'])->group(function () {
    
    Route::get('/mes-commandes', [Commande::class, 'index'])->name('commandes.index');

    // EXPEDITION
    Route::get('/expedition', [ExpeditionController::class, 'index'])->name('expedition.index');
    Route::post('/expedition/expedier/{id}', [ExpeditionController::class, 'expedier'])->name('expedition.expedier');
    
    // VENTE
    Route::get('/vente/ajouter', [VenteController::class, 'create'])->name('vente.create');
    Route::post('/vente/ajouter', [VenteController::class, 'store'])->name('vente.store');
    Route::get('/vente/categorie/ajouter', [VenteController::class, 'createCategory'])->name('vente.categorie.create');
    Route::post('/vente/categorie/ajouter', [VenteController::class, 'storeCategory'])->name('vente.categorie.store');
    Route::get('/api/sous-categories/{idCategorie}', [VenteController::class, 'getSousCategories']);
    Route::get('/vente/produit/{id}/modifier', [VenteController::class, 'edit'])->name('vente.edit');
    Route::put('/vente/produit/{id}', [VenteController::class, 'update'])->name('vente.update');
    Route::post('/vente/produit/{id}/image', [App\Http\Controllers\VenteController::class, 'addImage'])->name('vente.image.add');
    Route::delete('/vente/produit/{id}/image/{idPhoto}', [App\Http\Controllers\VenteController::class, 'deleteImage'])->name('vente.image.delete');

// Gestion des Variantes (Couleurs)
Route::post('/vente/produit/{id}/variante', [App\Http\Controllers\VenteController::class, 'addVariant'])->name('vente.variant.add');
Route::delete('/vente/produit/{id}/variante/{idColoris}', [App\Http\Controllers\VenteController::class, 'deleteVariant'])->name('vente.variant.delete');
Route::get('/vente/demandes', [VenteController::class, 'indexDemandes'])->name('vente.demandes.index');
Route::get('/vente/demande/{id}/traiter', [VenteController::class, 'createFromDemande'])->name('vente.demandes.create');
Route::post('/vente/demande/store', [VenteController::class, 'storeFromDemande'])->name('vente.demandes.store');
Route::get('/vente/en-attente', [VenteController::class, 'indexInvisible'])->name('vente.invisible.index');
Route::put('/vente/produit/{id}/publier', [VenteController::class, 'publierProduit'])->name('vente.publier');

    // SIÈGE
    Route::get('/siege/commandes-express', [SiegeController::class, 'index'])->name('siege.index');
    Route::post('/siege/commande/{id}/etat', [SiegeController::class, 'changerEtatLivraison'])->name('siege.etat.update');

    // STATS
    Route::get('/lancer-maj-stats', function () {
        $exitCode = Artisan::call('stats:update');
        return "<pre>Mise à jour terminée (Code $exitCode) : <br>" . Artisan::output() . "</pre>";
    });
});

Route::get('/stress-me', function () {
    $data = [];
    for ($i = 0; $i < 1000000; $i++) {
        $data[] = md5($i); // On fait faire des calculs inutiles au CPU
    }
    return "Test fini";
});


Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Formulaire pour saisir l'e-mail
// Vérifie que c'est exactement comme ça :
Route::get('forgotpassword', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Envoi du lien de réinitialisation
Route::post('forgotpassword', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Page d'affichage du formulaire de nouveau mot de passe (appelée par l'e-mail)
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');

// Action de mise à jour du mot de passe en base
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

Route::get('/typesvote', [TypeVoteController::class, 'index'])->name('typesvote.index');
// Création
Route::get('/typesvote/creer', [TypeVoteController::class, 'create'])->name('typesvote.create');
Route::post('/typesvote/stocker', [TypeVoteController::class, 'store'])->name('typesvote.store');

// Modification
Route::get('/typesvote/{id}/modifier', [TypeVoteController::class, 'edit'])->name('typesvote.edit');
Route::put('/typesvote/{id}/update', [TypeVoteController::class, 'update'])->name('typesvote.update');

Route::get('/typesvote/{id}/joueurs', [TypeVoteController::class, 'manageJoueurs'])->name('typesvote.joueurs');
Route::post('/typesvote/{id}/joueurs', [TypeVoteController::class, 'storeJoueurs'])->name('typesvote.store_joueurs');

Route::get('/publication/{id}', [App\Http\Controllers\PublicationDetail::class, 'show']);

// Enregistrement du commentaire (il faut que l'URL corresponde à celle du formulaire)
Route::post('/publication/{id}/comment', [App\Http\Controllers\PublicationDetail::class, 'storeComment'])->middleware('auth');


Route::post('/publication/{id}/comment', [CommentaireController::class, 'store'])->name('commentaires.store');