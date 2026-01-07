<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Panier; 
use App\Models\Transaction;
use App\Models\CarteBancaire;
use App\Models\Contenir;

class Payer extends Controller
{
    public function index()
    {
        $payers = Payer::all();
        
        return view('payer', compact('payers'));
    }
    public function processPayment(Request $request)
    {        
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Vous devez être connecté pour finaliser la commande.');
            }

            $userId = Auth::id();
            $idcb = $carte->idcb;
            
            $panierActif = Panier::where('idpersonne', $userId)->first();
            
            if (!$panierActif) {
                return redirect()->route('panier.getCartItems')->with('error', 'Aucun panier actif trouvé.');
            }
    
            $idPanier = $panierActif->idpanier;

            $transaction = new Transaction();
            $transaction->idpersonne = $userId;
            $transaction->idcb = $idcb;
            $transaction->save();

            $carte = new CarteBancaire();
            $carte->idpersonne = $userId;

            $carte->refcb = $request->input('card_number');
            $carte->nomcb = $request->input('card_name');
            $carte->dateexpirationcb = $request->input('expiry_date');
            $carte->save();

           // Contenir::where('idpanier', $idPanier)->delete();
           // Panier::where('idpanier', $idPanier)->delete();

           
            
            // --- 2. Validation des Données du Formulaire ---
            $request->validate([
                'email' => 'required|email|max:255',
                'nom_complet' => 'required|string|max:100',
                'adr' => 'required|string|max:100',
                'cpostal' => 'required|string|max:10',
                'ville' => 'required|string|max:50',
                'tel' => 'required|string|max:20',
                
                'card_number' => 'required|string',
                'card_name' => 'required|string|max:100',
                'expiry_date' => 'required|string',
                
                'delivery_method' => 'required|in:standard,express',
                'billing_address' => 'required|in:same,different',
            ]);   
            return redirect()->route('commandes.index')->with('success', 'Votre commande a été passée avec succès !');         
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // 1. (A) Validation des données entrantes
        $request->validate([
            'numero_de_carte' => 'required|string|between:13,19', 
            'nom_sur_carte' => 'required|string|max:200',
            'date_expiration' => 'required|string',
        ]);

        // (D) Vérification de l'authentification
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Vous devez être connecté.');
        }

        try {
            $carte = new CarteBancaire();

            $carte->refcb = $request->input('numero_de_carte');
            $carte->nomcb = $request->input('nom_sur_carte');
            $carte->dateexpirationcb = $request->input('date_expiration');        
            
            $carte->save();

            return redirect()->route('votre_route_de_confirmation')->with('success', 'Carte bancaire enregistrée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de la carte bancaire : ' . $e->getMessage());

            return back()->withInput()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la carte. Veuillez réessayer.');
        }
    }
}


