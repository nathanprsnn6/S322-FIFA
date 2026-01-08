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
}