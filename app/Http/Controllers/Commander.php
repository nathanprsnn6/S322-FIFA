<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Commande;

class Commander extends Controller{
    public function index()
    {
        $commanders = Commande::all();
        
        return view('commander', compact('commanders'));
    }

    public function processPayment()
    {
        return redirect('/')->with('success', 'Votre paiement a été traité avec succès !');
    }

    public function carteBancaire(){
        $carteBancaires = CarteBancaire::all();
        
        return view('carteBancaire', compact('carteBancaires'));
    }
      
}

