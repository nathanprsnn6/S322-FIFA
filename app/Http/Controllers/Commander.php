<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Panier; 
use App\Models\Transaction;
use App\Models\CarteBancaire;
use App\Models\Nation;
use App\Models\Contenir;

class Commander extends Controller{
    public function index()
    {
        $userId = Auth::id();
        $nations = Nation::orderBy('nomnation', 'asc')->get();
        $commanders = Commande::all();
        $panier = Panier::where('idpersonne', $userId)->first();
        $totalPanier = $panier ? $panier->prixpanier : 0;

        $contenirs = Contenir::where('idpanier', $panier->idpanier)
            ->with(['produit.illustrer', 'variante'])
            ->get();

        $prixpanier = 0;

        $contenirs->each(function ($item) use (&$prixpanier) {
            $prixUnitaire = $item->variante->prixproduit ?? 0;
            $item->prixLigne = $item->qteproduit * $prixUnitaire;
            $prixpanier += $item->prixLigne;
        });
        
        return view('commander', [
            'commanders' => $commanders,
            'nations' => $nations,
            'panier' => $panier,
            'contenirs' => $contenirs,
            'prixpanier' => $prixpanier,
            'totalPanier' => $totalPanier,
        ]);
    }

    public function processPayment(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'email' => 'required|email',
            'adr' => 'required|string',
            'cpostal' => 'required|string',
            // Valider tous les champs requis du formulaire commander.blade.php
            'card_number' => 'required|numeric|digits_between:13,19', // Exemple
            'card_name' => 'required|string',
            'cvv' => 'required|numeric|digits_between:3,4',
            'expiry_date' => 'required|string|date_format:m/y', 
            'nom_complet' => 'required|string', // Ajout de la validation pour le nom complet
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour finaliser la commande.');
        }

        $idpersonne = Auth::id();
        
        $panierActif = Panier::select('panier.*')
            ->leftJoin('commande', 'panier.idpanier', '=', 'commande.idpanier')
            ->where('panier.idpersonne', $idpersonne)
            ->whereNull('commande.idpanier')
            ->first();
        
        

        // --- Début du nouveau flux de traitement ---
        
        // 3. SIMULATION : Récupérer le montant total du panier (vous devez implémenter ceci)
        // Pour l'exemple, nous allons simuler un montant.
        $montantTotal = 50.00; // REMPLACER PAR LE CALCUL RÉEL DU PANIER
        
        $success = true; // Simuler la réussite du paiement
        
    

        try {
            // --- ÉTAPE 1 : Enregistrement de la Carte Bancaire (CarteBancaire) ---
            
            // On vérifie si la carte existe déjà pour ne pas la dupliquer
            $carteBancaire = CarteBancaire::firstOrCreate(
                [
                    'idpersonne' => $idpersonne,
                    // Utiliser le numéro de carte (refcb) comme identifiant unique
                    'refcb' => $request->card_number, 
                ],
                [
                    // Les autres champs seront mis à jour ou créés
                    'dateexpirationcb' => $request->expiry_date,
                    // CORRECTION: Changement de 'cvvcb' à 'ccvcb'
                    'ccvcb' => $request->cvv, 
                    'nomcb' => $request->card_name,
                ]
            );
            
            $idcb = $carteBancaire->idcb; 
            
            // --- ÉTAPE 2 : Enregistrement de la Transaction (Transaction) ---
            
            $transaction = new Transaction();
            // CORRECTION: Supprimer la ligne qui affecte refTransaction car elle n'existe pas en DB
            // LIGNE SUPPRIMÉE : $transaction->refTransaction = $refTransaction; 
            
            $transaction->idcb = $idcb;
            $transaction->datetransaction = now();
            $transaction->montanttransaction = $montantTotal;
            $transaction->save();
            
            // Récupérer la PK de la transaction (si elle est auto-incrémentée)
            $idtransaction = $transaction->idtransaction; 
            
            // --- ÉTAPE 3 : Enregistrement de la Commande (Commande) ---
            $commande = new Commande();
            $commande->idpanier = $panierActif->idpanier; 
            $commande->idtransaction = $idtransaction; // Lier la commande à la transaction
            $commande->idpersonne = $idpersonne;
            $commande->etatcommande = 'Confirmée - Paiement réussi'; 
            
            $commande->save();
            
            // 5. Redirection avec message de succès
            // Utiliser $idtransaction au lieu de $refTransaction dans le message
            return redirect('/produits')->with('success', 'Votre commande N°' . $commande->idcommande . ' a été enregistrée avec succès ! Transaction ID : ' . $idtransaction);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la commande: ' . $e->getMessage());
            
            // Si l'enregistrement échoue, il faut idéalement annuler la transaction de paiement simulée.
            // Dans le cas réel, on vérifie si la transaction est passée avant de créer la commande.
            
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la commande. Veuillez réessayer. Détails : ' . $e->getMessage());
        }
    }
    
    // ... Garder la méthode carteBancaire inchangée ...
    public function carteBancaire(){
        // Note : Ce contrôleur s'appelle "Commander", ce serait plus logique d'utiliser 
        // un "CarteBancaireController" si on devait vraiment gérer cette entité seule.
        $carteBancaires = CarteBancaire::all();
        
        return view('carteBancaire', compact('carteBancaires'));
    }
}