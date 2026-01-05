<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VenteController extends Controller
{

    public function create()
    {
        if (Auth::user()->idrole != 5) {
            return redirect('/')->with('error', 'Accès réservé au service vente.');
        }

        $nations = DB::table('nation')->orderBy('nomnation')->get();
        $competitions = DB::table('competition')->orderBy('nomcompetition')->get();
        $categories = DB::table('categorie')->orderBy('nomcategorie')->get();
        $coloris = DB::table('coloris')->orderBy('libellecoloris')->get();
        $tailles = DB::table('taille')->get(); 

        return view('create', compact('nations', 'competitions', 'categories', 'coloris', 'tailles'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->idrole != 5) { abort(403); }

        $request->validate([
            'titreproduit' => 'required|string|max:500',
            'descriptionproduit' => 'required|string',
            'idcategorie' => 'required|integer', 
            'idsouscategorie' => 'required|integer',
            'idnation' => 'required|integer',
            'idcompetition' => 'required|integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'idcoloris' => 'required|integer',
            'prixproduit' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $categorie = DB::table('categorie')->where('idcategorie', $request->idcategorie)->first();
            $sousCategorie = DB::table('sous_categorie')->where('idsouscategorie', $request->idsouscategorie)->first();

            $dossierCat = Str::slug($categorie->nomcategorie);
            $dossierSub = Str::slug($sousCategorie->nomsouscategorie);
            
            $cheminRelatif = "img/{$dossierCat}/{$dossierSub}";
            $cheminAbsolu = public_path($cheminRelatif);
            
            if (!file_exists($cheminAbsolu)) {
                mkdir($cheminAbsolu, 0777, true);
            }

            $extension = $request->photo->getClientOriginalExtension();
            $nomFichier = Str::slug($request->titreproduit) . '.' . $extension;
            
            $request->photo->move($cheminAbsolu, $nomFichier);
            $dbPath = $cheminRelatif . '/' . $nomFichier;

            $idProduit = DB::table('produit')->insertGetId([
                'idcompetition' => $request->idcompetition,
                'idsouscategorie' => $request->idsouscategorie,
                'idnation' => $request->idnation,
                'descriptionproduit' => $request->descriptionproduit,
                'titreproduit' => $request->titreproduit
            ], 'idproduit');

            $idPhoto = DB::table('photo')->insertGetId([
                'destinationphoto' => $dbPath
            ], 'idphoto');

            DB::table('illustrer')->insert([
                'idphoto' => $idPhoto,
                'idproduit' => $idProduit
            ]);

            DB::table('variante_produit')->insert([
                'idproduit' => $idProduit,
                'idcoloris' => $request->idcoloris,
                'prixproduit' => $request->prixproduit
            ]);

            $sizes = [
                1 => $request->stock_xs, 2 => $request->stock_s, 3 => $request->stock_m,
                4 => $request->stock_l, 5 => $request->stock_xl, 6 => $request->stock_xxl,
            ];

            foreach ($sizes as $idTaille => $stock) {
                if ($stock !== null && $stock >= 0) {
                    DB::table('reference')->insert([
                        'idproduit' => $idProduit,
                        'idcoloris' => $request->idcoloris,
                        'idtaille' => $idTaille,
                        'stock' => $stock
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('vente.create')->with('success', 'Produit créé ! Image : ' . $dbPath);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur technique : ' . $e->getMessage())->withInput();
        }
    }


    public function createCategory()
    {
        if (Auth::user()->idrole != 5) {
            return redirect('/')->with('error', 'Accès réservé au service vente.');
        }

        $categories = DB::table('categorie')->orderBy('nomcategorie')->get();
        $structureActuelle = DB::table('categorie')
            ->join('sous_categorie', 'categorie.idcategorie', '=', 'sous_categorie.idcategorie')
            ->select('categorie.nomcategorie', 'sous_categorie.nomsouscategorie')
            ->orderBy('categorie.nomcategorie')
            ->get();

        return view('create_categorie', compact('categories', 'structureActuelle'));
    }

    public function storeCategory(Request $request)
    {
        if (Auth::user()->idrole != 5) { abort(403); }

        $request->validate([
            'idcategorie_existante' => 'nullable|integer',
            'nomcategorie' => 'required_without:idcategorie_existante|nullable|string|max:50|unique:categorie,nomcategorie',
            'nomsouscategorie' => 'required|string|max:50',
        ]);

        try {
            DB::beginTransaction();

            $idCategorie = null;
            $nomDossierCat = "";

            if ($request->filled('idcategorie_existante')) {
                $idCategorie = $request->idcategorie_existante;
                $cat = DB::table('categorie')->where('idcategorie', $idCategorie)->first();
                $nomDossierCat = $cat->nomcategorie;
            } else {
                $idCategorie = DB::table('categorie')->insertGetId([
                    'nomcategorie' => $request->nomcategorie
                ], 'idcategorie');
                $nomDossierCat = $request->nomcategorie;
            }

            $existe = DB::table('sous_categorie')
                ->where('idcategorie', $idCategorie)
                ->where('nomsouscategorie', $request->nomsouscategorie)
                ->exists();

            if ($existe) {
                return back()->with('error', "La sous-catégorie existe déjà.")->withInput();
            }

            DB::table('sous_categorie')->insert([
                'idcategorie' => $idCategorie,
                'nomsouscategorie' => $request->nomsouscategorie
            ]);

            $dossierCat = Str::slug($nomDossierCat);
            $dossierSub = Str::slug($request->nomsouscategorie);
            $chemin = public_path("img/{$dossierCat}/{$dossierSub}");
            
            if (!file_exists($chemin)) {
                mkdir($chemin, 0777, true);
            }

            DB::commit();

            return redirect()->route('vente.categorie.create')
                ->with('success', "Structure mise à jour avec succès !");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur technique : ' . $e->getMessage())->withInput();
        }
    }


    public function edit($id)
    {
        if (Auth::user()->idrole != 5) { abort(403); }

        $produit = DB::table('produit')->where('idproduit', $id)->first();
        
        $sousCatActuelle = DB::table('sous_categorie')->where('idsouscategorie', $produit->idsouscategorie)->first();
        $idCategorieActuelle = $sousCatActuelle->idcategorie;

        $categories = DB::table('categorie')->orderBy('nomcategorie')->get();
        $sousCategoriesActuelles = DB::table('sous_categorie')->where('idcategorie', $idCategorieActuelle)->orderBy('nomsouscategorie')->get();
        $nations = DB::table('nation')->orderBy('nomnation')->get();
        $competitions = DB::table('competition')->orderBy('nomcompetition')->get();
        $coloris = DB::table('coloris')->orderBy('libellecoloris')->get();

        $variante = DB::table('variante_produit')->where('idproduit', $id)->first();

        return view('edit_produit', compact('produit', 'categories', 'sousCategoriesActuelles', 'idCategorieActuelle', 'nations', 'competitions', 'variante', 'coloris'));
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->idrole != 5) { abort(403); }

        $request->validate([
            'titreproduit' => 'required|string|max:500',
            'descriptionproduit' => 'required|string',
            'idsouscategorie' => 'required|integer',
            'idnation' => 'required|integer',
            'idcompetition' => 'required|integer',
            'prixproduit' => 'required|numeric|min:0',
            'idcoloris' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            DB::table('produit')->where('idproduit', $id)->update([
                'idcompetition' => $request->idcompetition,
                'idsouscategorie' => $request->idsouscategorie,
                'idnation' => $request->idnation,
                'descriptionproduit' => $request->descriptionproduit,
                'titreproduit' => $request->titreproduit
            ]);

            $oldColor = $request->input('old_idcoloris');
            $newColor = $request->idcoloris;

            if ($oldColor != $newColor) {
                $existeDeja = DB::table('variante_produit')
                    ->where('idproduit', $id)
                    ->where('idcoloris', $newColor)
                    ->exists();

                if ($existeDeja) {
                    DB::rollback();
                    return back()->with('error', "Ce produit possède déjà une variante dans cette couleur.");
                }
            }

            DB::table('variante_produit')
                ->where('idproduit', $id)
                ->where('idcoloris', $oldColor)
                ->update([
                    'idcoloris' => $newColor,
                    'prixproduit' => $request->prixproduit
                ]);

            DB::commit();
            return redirect()->route('produits.index')->with('success', 'Produit modifié avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur technique : ' . $e->getMessage());
        }
    }


    public function getSousCategories($idCategorie)
    {
        $sousCategories = DB::table('sous_categorie')
            ->where('idcategorie', $idCategorie)
            ->orderBy('nomsouscategorie')
            ->get();
            
        return response()->json($sousCategories);
    }
}