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
        $savedCards = [];
        if ($userId) {
            $savedCards = CarteBancaire::where('idpersonne', $userId)->get();
        }
        $nations = Nation::select('idnation', 'nomnation', 'codetel')
            ->orderBy('nomnation', 'asc')
            ->get();
        $commanders = Commande::all();
        $panier = Panier::where('idpersonne', $userId)
            ->where('panieractif', '=' ,'true')
            ->first();
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

        $user = \DB::table('utilisateur')->where('idpersonne', $userId)->first();
        $personne = \DB::table('personne')->where('idpersonne', $userId)->first();

        $userNation = Nation::where('idnation', $user->idnation)->first();

        $userData = [
            'email' => $user->courriel ?? '',
            'nom_complet' => trim(($personne->nom ?? '') . ' ' . ($personne->prenom ?? '')),
            'pays' => $userNation ? $userNation->idnation : '',
            'cp' => $user->cp ?? '',
        ];
            
        return view('commander', [
            'commanders' => $commanders,
            'nations' => $nations,
            'panier' => $panier,
            'contenirs' => $contenirs,
            'prixpanier' => $prixpanier,
            'totalPanier' => $totalPanier,
            'savedCards'=> $savedCards,
            'userData'=> $userData,
        ]);
    }
}