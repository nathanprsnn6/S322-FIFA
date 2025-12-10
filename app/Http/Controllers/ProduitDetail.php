<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Taille;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProduitDetail extends Controller
{
    public function show($id)
    {
        $produit = Produit::findOrFail($id);
        $photo = 
        DB::table('photo')
        ->join('illustrer', 'photo.idphoto', '=', 'illustrer.idphoto')
        ->where('illustrer.idproduit', $id)
        ->select('photo.destinationphoto')
        ->first();

        $tailles = Taille::whereIn('idtaille', function($query) use ($id) {
            $query->select('idtaille')
                  ->from('reference')
                  ->where('idproduit', $id)
                  ->where('stock', '>', 0);
        })->get();

        $variantes = DB::table('variante_produit')
            ->join('coloris', 'variante_produit.idcoloris', '=', 'coloris.idcoloris')
            ->where('variante_produit.idproduit', $id)
            ->select('coloris.idcoloris', 'coloris.libellecoloris', 'variante_produit.prixproduit')
            ->get();

        $premierIdColoris = $variantes->first()->idcoloris ?? 0;

        $stock = DB::table('reference')
        ->where('idproduit', $id)
        ->where('idcoloris', $premierIdColoris)
        ->pluck('stock', 'idtaille')
        ->toArray();


        
        
        

        $produitsSimilaires = DB::table('produit')
            ->join('variante_produit', 'produit.idproduit', '=', 'variante_produit.idproduit')
            ->join('illustrer', 'produit.idproduit', '=', 'illustrer.idproduit')
            ->join('photo', 'illustrer.idphoto', '=', 'photo.idphoto')
            ->where('produit.idsouscategorie', $produit->idsouscategorie)
            ->where('produit.idproduit', '!=', $id)
            ->select('produit.idproduit', 'produit.titreproduit', DB::raw('MIN(variante_produit.prixproduit) as prix'),DB::raw('MAX(photo.destinationphoto) as destinationphoto'))
            ->groupBy('produit.idproduit', 'produit.titreproduit')
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($produitsSimilaires->isEmpty()) {
            $produitsSimilaires = DB::table('produit')
                ->join('variante_produit', 'produit.idproduit', '=', 'variante_produit.idproduit')
                ->join('illustrer', 'produit.idproduit', '=', 'illustrer.idproduit')
                ->join('photo', 'illustrer.idphoto', '=', 'photo.idphoto')
                ->where('produit.idproduit', '!=', $id)
                ->select('produit.idproduit', 'produit.titreproduit', DB::raw('MIN(variante_produit.prixproduit) as prix'),DB::raw('MAX(photo.destinationphoto) as destinationphoto'))
                ->groupBy('produit.idproduit', 'produit.titreproduit')
                ->inRandomOrder()
                ->take(4)
                ->get();
        }

        return view('produitDetails', compact('produit', 'tailles', 'variantes', 'produitsSimilaires','photo', 'stock'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required|exists:taille,idtaille',
            'color' => 'required|exists:coloris,idcoloris',
        ]);
        try {

            return redirect()->route('produits.index')->with('success', 'Ajout au panier du produit réussi avec succès !');
        } catch (\Exception $e) {
            Log::error("Erreur d'ajout au panier : " . $e->getMessage());
            return back()->withErrors(['error' => "Erreur technique : " . $e->getMessage()])->withInput();
        }
    }
}