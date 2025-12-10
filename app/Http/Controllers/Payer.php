<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class Payer extends Controller
{
    public function index()
    {
        $payers = Payer::all();
        
        return view('payer', compact('payers'));
    }
    public function processPaiement(Request $request)
    {        
        $request->validate([
            'idcommande' => 'required|exists:orders,id',
            'idcb' => 'required|numeric',
            // ... autres validations
        ]);

        // 2. Logique de paiement (intégration avec Stripe, PayPal, etc.)
        // ...

        // 3. Mise à jour du statut de la commande
        // Payer::create([...]); // Exemple d'enregistrement de transaction

        // 4. Redirection avec un message de succès
        return redirect()->back()->with('success', 'Votre commande a été réglée avec succès !');
    }
}


