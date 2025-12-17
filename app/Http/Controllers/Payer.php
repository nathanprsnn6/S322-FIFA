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
        ]);

        return redirect()->back()->with('success', 'Votre commande a été réglée avec succès !');
    }
}


