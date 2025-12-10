<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Nation;
use App\Models\Categorie;
use App\Models\SousCategorie; 
use App\Models\Coloris;
use App\Models\Taille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduitTest extends Controller
{
    public function index(Request $request)
    {
        $nations = Nation::all();
        $categories = Categorie::all();
        $allColors = Coloris::all();
        $allSizes = Taille::all();

        $sousCategoriesQuery = SousCategorie::query();
        if ($request->filled('cat')) {
            $sousCategoriesQuery->where('idcategorie', $request->cat);
        }
        $availableSubCats = $sousCategoriesQuery->get();


        $query = DB::table('produit')
        ->select(
        'produit.*',
        DB::raw('MIN(variante_produit.prixproduit) as min_prix'),
        DB::raw('MAX(variante_produit.prixproduit) as max_prix'),
        DB::raw('MAX(photo.destinationphoto) as destinationphoto')
        )
        ->join('variante_produit', 'produit.idproduit', '=', 'variante_produit.idproduit')
        ->join('sous_categorie', 'produit.idsouscategorie', '=', 'sous_categorie.idsouscategorie')
        ->join('illustrer', 'produit.idproduit', '=', 'illustrer.idproduit')
        ->join('photo', 'illustrer.idphoto', '=', 'photo.idphoto')
        ->groupBy('produit.idproduit');
        if ($request->filled('cat')) {
            $query->where('sous_categorie.idcategorie', $request->cat);
        }
        if ($request->filled('nation')) {
            $query->where('produit.idnation', $request->nation);
        }

        if ($request->filled('subcats')) {

            $query->whereIn('produit.idsouscategorie', $request->subcats);
        }

        if ($request->filled('colors') || $request->filled('sizes')) {
            $query->whereExists(function ($subQuery) use ($request) {
                $subQuery->select(DB::raw(1))
                    ->from('reference')
                    ->whereColumn('reference.idproduit', 'produit.idproduit');

                if ($request->filled('colors')) {
                    $subQuery->whereIn('reference.idcoloris', $request->colors);
                }
                if ($request->filled('sizes')) {
                    $subQuery->whereIn('reference.idtaille', $request->sizes);
                }
            });
        }


        if ($request->filled('sort')) {
            $query->orderBy('min_prix', $request->sort);
        }

        $lesProduits = $query->get();

        return view('produitTest', [
            'produits' => $lesProduits,
            'nations' => $nations,
            'categories' => $categories,
            'allColors' => $allColors,
            'allSizes' => $allSizes,
            'availableSubCats' => $availableSubCats, 
            

            'currentNation' => $request->nation,
            'currentCat' => $request->cat,
            'selectedColors' => $request->colors ?? [],
            'selectedSizes' => $request->sizes ?? [],
            'selectedSubCats' => $request->subcats ?? [] 
        ]);
    }
}