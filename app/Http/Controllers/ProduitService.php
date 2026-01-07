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
        // On joint 'produit' et 'variante_produit' via 'idproduit'
        $produits = DB::table('produit')
            ->join('variante_produit', 'produit.idproduit', '=', 'variante_produit.idproduit')
            ->select(
                'produit.idproduit', 
                'produit.titreproduit', // ou 'titreproduit' selon votre base
                'variante_produit.prixproduit'
            )
            ->whereNull('variante_produit.prixproduit')
            ->orWhere('variante_produit.prixproduit', '<=', 0)
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