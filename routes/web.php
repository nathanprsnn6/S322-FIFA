<?php

use App\Http\Controllers\ProduitService;
use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\CarteBancaireController;
use App\Http\Controllers\Commander;
use App\Http\Controllers\Contenir;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\Payer;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Commande;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\PublicationDetail;
use App\Http\Controller\ExpeditionService;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- LISTES & TESTS ---
Route::get('/personnes', [PersonneTest::class, 'index']);
Route::get('/utilisateurs', [UtilisateurTest::class, 'index']);

// --- PRODUITS ---
Route::get('/produits', [ProduitTest::class, 'index'])->name('produits.index');
Route::get('/produit/{id}', [ProduitDetail::class, 'show'])->name('produit.show');
Route::post('/produits', [ProduitDetail::class, 'store'])->name('produit.store');
Route::get('produitService', [ProduitService::class, 'produitsSansPrix'])->name('produitService.sans_prix');
Route::post('/produits/save-prix', [ProduitService::class, 'updatePrix'])->name('produits.save_prix');
// --- INSCRIPTION ---
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

// --- PANIER & COMMANDE ---
Route::get('/panier', [PanierController::class, 'getCartItems'])->name('panier.getCartItems');
Route::get('/commander', [Commander::class, 'index'])->name('commander.index');
Route::get('/carteBancaire', [Commander::class, 'carteBancaire'])->name('commander.carteBancaire');
Route::post('/commander/paiement', [Commander::class, 'processPayment'])->name('commander.processPayment');

Route::get('/payer', [Payer::class, 'index'])->name('payer.index');
Route::post('/payer/effectuer', [Payer::class, 'processPaiement'])->name('payer.effectuer');

// --- PUBLICATIONS ---
Route::get('/publication', [PublicationController::class, 'index'])->name('publication.index');
Route::get('/publication/{id}', [PublicationDetail::class, 'show'])->name('publication.show');

// --- ROUTES PROTÉGÉES (AUTH) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/mes-commandes', [Commande::class, 'index'])->name('commandes.index');
    Route::get('/expedition', [ExpeditionController::class, 'index'])->name('expedition.index');
    
    // Route utilitaire pour les stats
    Route::get('/lancer-maj-stats', function () {
        $exitCode = Artisan::call('stats:update');
        $output = Artisan::output();
        return "<pre>Mise à jour terminée (Code $exitCode) : <br>" . $output . "</pre>";
    });
});