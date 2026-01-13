<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produit;

class ProduitService extends Controller
{
    /**
     * Récupère les variantes de produits sans prix
     */
    public function produitsSansPrix()
    {
        $produits = DB::table('produit')
            // 1. Jointure vers les variantes
            ->join('variante_produit', 'produit.idproduit', '=', 'variante_produit.idproduit')
            
            // 2. CORRECTION : On lie 'coloris' directement à 'variante_produit' 
            // via l'idcoloris qui doit se trouver dans variante_produit
            ->join('coloris', 'variante_produit.idcoloris', '=', 'coloris.idcoloris')
            
            ->select(
                'produit.idproduit', 
                'produit.titreproduit',
                'variante_produit.prixproduit',
                'coloris.libellecoloris'
            )
            ->where(function($query) {
                $query->whereNull('variante_produit.prixproduit')
                      ->orWhere('variante_produit.prixproduit', '<=', 0);
            })
            ->get();
    
        return view('produitService', compact('produits'));
    }

    /**
     * Enregistre le prix dans la table variante_produit
     */
    public function updatePrix(Request $request)
    {
        $request->validate([
            'idproduit' => 'required|exists:variante_produit,idproduit',
            'prix' => 'required|numeric|min:0.01'
        ]);

        DB::table('variante_produit')
            ->where('idproduit', $request->idproduit)
            ->update(['prixproduit' => $request->prix]);

        return redirect()->back()->with('success', 'Prix mis à jour avec succès !');
    }
}