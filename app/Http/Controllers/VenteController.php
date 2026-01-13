<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
        
        $variantes = DB::table('variante_produit')
            ->join('coloris', 'variante_produit.idcoloris', '=', 'coloris.idcoloris')
            ->where('variante_produit.idproduit', $id)
            ->select('variante_produit.*', 'coloris.libellecoloris', 'coloris.hexacoloris')
            ->get();

        $idsColorisUtilises = $variantes->pluck('idcoloris')->toArray();
        $colorisDisponibles = DB::table('coloris')
            ->whereNotIn('idcoloris', $idsColorisUtilises)
            ->orderBy('libellecoloris')
            ->get();

        $photos = DB::table('illustrer')
            ->join('photo', 'illustrer.idphoto', '=', 'photo.idphoto')
            ->where('illustrer.idproduit', $id)
            ->get();

        return view('edit_produit', compact(
            'produit', 'categories', 'sousCategoriesActuelles', 
            'idCategorieActuelle', 'nations', 'competitions', 
            'variantes', 'colorisDisponibles', 'photos'
        ));
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
        ]);

        try {
            DB::table('produit')->where('idproduit', $id)->update([
                'idcompetition' => $request->idcompetition,
                'idsouscategorie' => $request->idsouscategorie,
                'idnation' => $request->idnation,
                'descriptionproduit' => $request->descriptionproduit,
                'titreproduit' => $request->titreproduit
            ]);

            return back()->with('success', 'Informations générales mises à jour.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function addImage(Request $request, $id)
    {
        if (Auth::user()->idrole != 5) { abort(403); }
        $request->validate(['photo' => 'required|image|max:2048']);

        try {
            DB::beginTransaction();
            
            $produit = DB::table('produit')->where('idproduit', $id)->first();
            $sousCat = DB::table('sous_categorie')->where('idsouscategorie', $produit->idsouscategorie)->first();
            $cat = DB::table('categorie')->where('idcategorie', $sousCat->idcategorie)->first();

            $dossierCat = Str::slug($cat->nomcategorie);
            $dossierSub = Str::slug($sousCat->nomsouscategorie);

            $cheminRelatif = "img/{$dossierCat}/{$dossierSub}";
            $cheminAbsolu = public_path($cheminRelatif);
            
            if (!file_exists($cheminAbsolu)) { mkdir($cheminAbsolu, 0777, true); }

            $extension = $request->photo->getClientOriginalExtension();
            $nomFichier = Str::slug($produit->titreproduit) . '-' . uniqid() . '.' . $extension;
            $request->photo->move($cheminAbsolu, $nomFichier);
            $dbPath = $cheminRelatif . '/' . $nomFichier;

            $idPhoto = DB::table('photo')->insertGetId(['destinationphoto' => $dbPath], 'idphoto');
            DB::table('illustrer')->insert(['idphoto' => $idPhoto, 'idproduit' => $id]);

            DB::commit();
            return back()->with('success', 'Image ajoutée.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur upload : ' . $e->getMessage());
        }
    }

    public function deleteImage($idProduit, $idPhoto)
    {
        if (Auth::user()->idrole != 5) { abort(403); }

        $count = DB::table('illustrer')->where('idproduit', $idProduit)->count();
        if ($count <= 1) {
            return back()->with('error', 'Impossible de supprimer la dernière image du produit.');
        }

        try {
            DB::beginTransaction();
            
            $photo = DB::table('photo')->where('idphoto', $idPhoto)->first();
            
            DB::table('illustrer')->where('idphoto', $idPhoto)->where('idproduit', $idProduit)->delete();
            DB::table('photo')->where('idphoto', $idPhoto)->delete();

            if ($photo && File::exists(public_path($photo->destinationphoto))) {
                File::delete(public_path($photo->destinationphoto));
            }

            DB::commit();
            return back()->with('success', 'Image supprimée.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur suppression : ' . $e->getMessage());
        }
    }

    public function addVariant(Request $request, $id)
    {
        if (Auth::user()->idrole != 5) { abort(403); }
        $request->validate([
            'idcoloris' => 'required|integer',
            'prixproduit' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();
            
            DB::table('variante_produit')->insert([
                'idproduit' => $id,
                'idcoloris' => $request->idcoloris,
                'prixproduit' => $request->prixproduit
            ]);

            $tailles = DB::table('taille')->get();
            foreach($tailles as $taille) {
                DB::table('reference')->insert([
                    'idproduit' => $id,
                    'idcoloris' => $request->idcoloris,
                    'idtaille' => $taille->idtaille,
                    'stock' => 0 
                ]);
            }

            DB::commit();
            return back()->with('success', 'Nouvelle variante couleur ajoutée.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur ajout variante : ' . $e->getMessage());
        }
    }

    public function deleteVariant($idProduit, $idColoris)
    {
        if (Auth::user()->idrole != 5) { abort(403); }

        try {
            DB::beginTransaction();

            DB::table('reference')
                ->where('idproduit', $idProduit)
                ->where('idcoloris', $idColoris)
                ->delete();

            DB::table('contenir')
                ->where('idproduit', $idProduit)
                ->where('idcoloris', $idColoris)
                ->delete();

            DB::table('variante_produit')
                ->where('idproduit', $idProduit)
                ->where('idcoloris', $idColoris)
                ->delete();

            DB::commit();
            return back()->with('success', 'Variante supprimée.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur suppression variante : ' . $e->getMessage());
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


    

    public function indexDemandes()
    {
        $demandes = DB::table('demandeproduit')
            ->join('personne', 'demandeproduit.idpersonne', '=', 'personne.idpersonne')
            ->where('est_traite', false)
            ->select('demandeproduit.*', 'personne.nom', 'personne.prenom')
            ->get();

        return view('vente_demandes', compact('demandes'));
    }

    public function createFromDemande($id)
    {
        $demande = DB::table('demandeproduit')->where('iddemandeproduit', $id)->first();
        
        $nations = DB::table('nation')->orderBy('nomnation')->get();
        $competitions = DB::table('competition')->orderBy('nomcompetition')->get();
        $categories = DB::table('categorie')->orderBy('nomcategorie')->get();

        return view('vente_create_from_demande', compact('demande', 'nations', 'competitions', 'categories'));
    }

    public function storeFromDemande(Request $request)
    {
        $request->validate([
            'iddemandeproduit' => 'required|integer', 
            'titreproduit' => 'required',
            'descriptionproduit' => 'required',
            'idnation' => 'required',
            'idcompetition' => 'required',
            'idsouscategorie' => 'required',
            'photos' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $idProduit = DB::table('produit')->insertGetId([
                'titreproduit' => $request->titreproduit,
                'descriptionproduit' => $request->descriptionproduit,
                'idnation' => $request->idnation,
                'idcompetition' => $request->idcompetition,
                'idsouscategorie' => $request->idsouscategorie
            ], 'idproduit');

            $firstColor = DB::table('coloris')->first();

            if ($firstColor) {
                DB::table('variante_produit')->insert([
                    'idproduit' => $idProduit,
                    'idcoloris' => $firstColor->idcoloris,
                    'prixproduit' => 0 
                ]);
            }

            if ($request->hasFile('photos')) {
                $sousCat = DB::table('sous_categorie')->where('idsouscategorie', $request->idsouscategorie)->first();
                $cat = DB::table('categorie')->where('idcategorie', $sousCat->idcategorie)->first();

                $dossierCat = Str::slug($cat->nomcategorie);
                $dossierSub = Str::slug($sousCat->nomsouscategorie);
                $cheminRelatif = "img/{$dossierCat}/{$dossierSub}";
                $cheminAbsolu = public_path($cheminRelatif);

                if (!file_exists($cheminAbsolu)) {
                    mkdir($cheminAbsolu, 0777, true);
                }

                foreach ($request->file('photos') as $photo) {
                    $extension = $photo->getClientOriginalExtension();
                    $nomFichier = Str::slug($request->titreproduit) . '-' . uniqid() . '.' . $extension;
                    
                    $photo->move($cheminAbsolu, $nomFichier);
                    $dbPath = $cheminRelatif . '/' . $nomFichier;

                    $idPhoto = DB::table('photo')->insertGetId([
                        'destinationphoto' => $dbPath
                    ], 'idphoto');

                    DB::table('illustrer')->insert([
                        'idphoto' => $idPhoto,
                        'idproduit' => $idProduit
                    ]);
                }
            }

            DB::table('demandeproduit')
                ->where('iddemandeproduit', $request->iddemandeproduit)
                ->update(['est_traite' => true]);

            DB::commit();
            return redirect()->route('vente.demandes.index')->with('success', 'Produit créé et demande marquée comme traitée.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    public function indexInvisible()
    {
        if (Auth::user()->idrole != 5) { abort(403); }

        $produits = DB::table('produit')
            ->leftJoin('illustrer', 'produit.idproduit', '=', 'illustrer.idproduit')
            ->leftJoin('photo', 'illustrer.idphoto', '=', 'photo.idphoto')
            ->select('produit.*', 'photo.destinationphoto')
            ->where('produit.visible', false)
            ->orderBy('produit.idproduit', 'desc')
            ->get()
            ->unique('idproduit');

        return view('vente_invisible', compact('produits'));
    }

    public function publierProduit($id)
    {
        if (Auth::user()->idrole != 5) { abort(403); }

        DB::table('produit')->where('idproduit', $id)->update(['visible' => true]);

        return redirect()->route('vente.invisible.index')
            ->with('success', 'Le produit #' . $id . ' est maintenant EN LIGNE et visible par les clients.');
    }
}