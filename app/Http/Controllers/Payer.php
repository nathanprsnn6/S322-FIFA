<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Panier; 
use App\Models\Transaction;
use App\Models\CarteBancaire;
use App\Models\Contenir;

class Payer extends Controller
{
    /**
     * Traite le paiement, enregistre la carte bancaire et crée la transaction.
     */
    public function processPayment(Request $request)
    {        
        dd('A. Entrée dans le contrôleur');
        // 1. Validation des Données
        $request->validate([
            'email' => 'required|email|max:255',
            'nom_complet' => 'required|string|max:100',
            
            // ADRESSE
            'adr' => 'required|string|max:100',
            'cp' => 'required|string|max:10',      // Alignement avec commander.blade.php
            'ville_in' => 'required|string|max:50', // Alignement avec le champ caché
            'tel' => 'required|string|max:20',
            
            // PAIEMENT (directement envoyés, plus en hidden)
            'card_number' => 'required|string|between:13,19', 
            'card_name' => 'required|string|max:100',
            'expiry_date' => 'required|string',
            'cvv' => 'required|string|max:4', // Ajout de la validation du CVV
            
            // OPTIONS
            'delivery_method' => 'required|in:standard,express',
            'billing_address' => 'required|in:same,different',
        ]);   
        dd('B. Validation réussie');
        
        try {
            // Vérification de l'authentification
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Vous devez être connecté pour finaliser la commande.');
            }

            $userId = Auth::id();
            
            // 2. Récupération des articles du panier
            $panierActif = Panier::where('idpersonne', $userId)->first();
            
            if (!$panierActif) {
                return redirect()->route('produits')->with('error', 'Aucun panier actif trouvé. Votre session de commande a expiré.'); 
            }
            
            $idPanier = $panierActif->idpanier;
            $contenirs = Contenir::where('idpanier', $idPanier)->get();

            if ($contenirs->isEmpty()) {
                return redirect()->route('panier.getCartItems')->with('error', 'Votre panier est vide.');
            }
            
            // 3. Enregistrement de la Carte Bancaire
            // NOTE : Le CVV (input('cvv')) n'est PAS stocké dans la BDD pour des raisons de sécurité.
            $carte = new CarteBancaire();
            $carte->idpersonne = $userId;
            $carte->refcb = $request->input('card_number');
            $carte->nomcb = $request->input('card_name');
            $carte->dateexpirationcb = $request->input('expiry_date');
            dd('C. Avant sauvegarde de la carte');
            $carte->save();            
            //$idcb = $carte->idcb; 

            dd('E. Après idcb');
            
            // 4. Création de la Transaction (Commande)
            $transaction = new Transaction();
            $transaction->idpersonne = $userId;
          //  $transaction->idcb = $idcb;
            $transaction->datecommande = now();
            
            // Calculer le montant total (y compris les frais de livraison si nécessaire)
            $montantTotal = $contenirs->sum(fn($item) => $item->prixLigne); 
            // Ajouter la logique pour ajouter les frais de livraison ($9.00 ou $16.50) ici.
        
            $transaction->montant = $montantTotal; 
            $transaction->adresse_livraison = $request->input('adr') . ', ' . $request->input('cp') . ' ' . $request->input('ville_in');
            $transaction->save();

            // 5. Nettoyage du panier après commande
            // Contenir::where('idpanier', $idPanier)->delete(); 
            // Panier::where('idpanier', $idPanier)->delete(); 
            
            return redirect('/')->with('success', 'Votre commande a été passée avec succès !');  
    
        }
        catch (\Exception $e) {
            Log::error('Erreur lors de la finalisation de la commande : ' . $e->getMessage());
            // Retourne une réponse pour éviter la page blanche
            return back()->withInput()->with('error', 'Une erreur est survenue lors du traitement de votre commande. Veuillez réessayer.');
        }
    }
}