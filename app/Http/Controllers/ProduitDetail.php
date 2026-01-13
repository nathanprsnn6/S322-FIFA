<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Taille;
use App\Models\Contenir;
use App\Models\Panier;
use App\Models\variant_produit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProduitDetail extends Controller
{
    public function show($id)
    {
        $produit = Produit::findOrFail($id);
        $recentIds = session()->get('recent_products', []);

        if (($key = array_search($id, $recentIds)) !== false) {
            unset($recentIds[$key]);
        }
        array_unshift($recentIds, $id);

        $recentIds = array_slice($recentIds, 0, 5);

        session()->put('recent_products', $recentIds);

        $idsToFetch = array_filter($recentIds, function($val) use ($id) {
            return $val != $id;
        });

        $produitsConsultes = collect(); 
        if (!empty($idsToFetch)) {
            $results = DB::table('produit')
                ->join('variante_produit', 'produit.idproduit', '=', 'variante_produit.idproduit')
                ->join('illustrer', 'produit.idproduit', '=', 'illustrer.idproduit')
                ->join('photo', 'illustrer.idphoto', '=', 'photo.idphoto')
                ->whereIn('produit.idproduit', $idsToFetch)
                ->select(
                    'produit.idproduit', 
                    'produit.titreproduit', 
                    DB::raw('MIN(variante_produit.prixproduit) as prix'),
                    DB::raw('MAX(photo.destinationphoto) as destinationphoto')
                )
                ->groupBy('produit.idproduit', 'produit.titreproduit')
                ->get();

            $produitsConsultes = $results->sortBy(function ($model) use ($idsToFetch) {
                return array_search($model->idproduit, array_values($idsToFetch));
            });
        }

        $photos = DB::table('photo')
            ->join('illustrer', 'photo.idphoto', '=', 'illustrer.idphoto')
            ->where('illustrer.idproduit', $id)
            ->select('photo.destinationphoto')
            ->orderBy('photo.idphoto', 'asc')
            ->get();

        $tailles = Taille::whereIn('idtaille', function($query) use ($id) {
            $query->select('idtaille')
                  ->from('reference')
                  ->where('idproduit', $id)
                  ->where('stock', '>', 0);
        })->orderBy('idtaille')->get();

        $variantes = DB::table('variante_produit')
            ->join('coloris', 'variante_produit.idcoloris', '=', 'coloris.idcoloris')
            ->where('variante_produit.idproduit', $id)
            ->where('variante_produit.prixproduit', '>', 0) 
            ->select('coloris.idcoloris', 'coloris.libellecoloris', 'variante_produit.prixproduit')
            ->get();

        $references = DB::table('reference')->where('idproduit', $id)->get(['idcoloris', 'idtaille', 'stock']);
        $stock = [];
        foreach ($references as $ref) {
            $stock[$ref->idcoloris][$ref->idtaille] = $ref->stock;
        }

        $premierIdColoris = $variantes->first()->idcoloris ?? 0;
        $premiereTaille = $tailles->first()->idtaille ?? null;
        $maxQuantity = ($premierIdColoris && $premiereTaille && isset($stock[$premierIdColoris][$premiereTaille])) 
                        ? $stock[$premierIdColoris][$premiereTaille] : 1;

        $produitsSimilaires = DB::table('produit')
            ->join('variante_produit', 'produit.idproduit', '=', 'variante_produit.idproduit')
            ->join('illustrer', 'produit.idproduit', '=', 'illustrer.idproduit')
            ->join('photo', 'illustrer.idphoto', '=', 'photo.idphoto')
            ->where('produit.idsouscategorie', $produit->idsouscategorie)
            ->where('produit.idproduit', '!=', $id)
            ->select('produit.idproduit', 'produit.titreproduit', DB::raw('MIN(variante_produit.prixproduit) as prix'), DB::raw('MAX(photo.destinationphoto) as destinationphoto'))
            ->groupBy('produit.idproduit', 'produit.titreproduit')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('produitDetails', compact('produit', 'tailles', 'variantes', 'produitsSimilaires', 'produitsConsultes', 'photos', 'stock', 'maxQuantity', 'premierIdColoris'));
    }


    public function createGuestUser()
    {
        $guestNom = 'Invité';
        $guestPrenom = 'Guest-' . Str::substr(Str::uuid(), 0, 8);

        $idPersonne = DB::table('personne')->insertGetId([
            'nom' => $guestNom,
            'prenom' => $guestPrenom,
            'lieunaissance' => 'Inconnu',
            'datenaissance' => '2025-01-01',
        ],'idpersonne');

        DB::table('utilisateur')->insert([
            'idpersonne' => $idPersonne,
            'idnation' => 1,
            'idrole' => 1,
            'naiss_idnation' => 1,
            'langue_idnation' => 1,
            'courriel' => $guestPrenom . '@invite.com',
            'cp'  => 11111,
            'ville' => "Inconnu",
            'mdp' => "Inconnu",
        ]);

        return $idPersonne;
    }

    public function store(Request $request)
    {
        $request->validate([
            'produitId' => 'required|exists:produit,idproduit',
            'size'      => 'required|exists:taille,idtaille',
            'color'     => 'required|exists:coloris,idcoloris',
            'quantity'  => 'required|numeric|min:1',
        ]);

        $produitId = $request->input('produitId');
        $selectedSize = $request->input('size');
        $selectedColor = $request->input('color');
        $quantityToAdd = (int) $request->input('quantity', 1);

        $panierId = null;
        $userId = Auth::id();

        try {
            $limiteDate = Carbon::now()->subDays(7);

            if ($userId) {
                $panier = DB::table('panier')
                    ->where('idpersonne', $userId)
                    ->where('panieractif', '=' ,'true')
                    ->where('datecreationpanier', '>=', $limiteDate)
                    ->first();

                if (!$panier) {
                    $panierId = DB::table('panier')->insertGetId([
                        'idpersonne'        => $userId,
                        'prixpanier'        => 0,
                        'datecreationpanier'=> now(),
                    ], 'idpanier');
                } else {
                    $panierId = $panier->idpanier;
                }
            } else {
                $guestUserId = session('guest_user_id');

                if (!$guestUserId) {
                    $guestUserId = $this->createGuestUser();
                    session(['guest_user_id' => $guestUserId]);
                }

                $panier = DB::table('panier')
                    ->where('idpersonne', $guestUserId)
                    ->where('panieractif', '=' ,'true')
                    ->where('datecreationpanier', '>=', $limiteDate)
                    ->first();

                if (!$panier) {
                    $panierId = DB::table('panier')->insertGetId([
                        'idpersonne'        => $guestUserId,
                        'prixpanier'        => 0,
                        'datecreationpanier'=> now(),
                    ], 'idpanier');
                } else {
                    $panierId = $panier->idpanier;
                }
            }

            if (!$panierId) {
                return back()
                    ->withErrors(['error' => "Impossible de créer ou récupérer le panier."])
                    ->withInput();
            }

            $existingCartItem = DB::table('contenir')
                ->where('idpanier', $panierId)
                ->where('idproduit', $produitId)
                ->where('idtaille', $selectedSize)
                ->where('idcoloris', $selectedColor)
                ->first();

            if ($existingCartItem) {
                DB::table('contenir')
                    ->where('idpanier', $panierId)
                    ->where('idproduit', $produitId)
                    ->where('idtaille', $selectedSize)
                    ->where('idcoloris', $selectedColor)
                    ->increment('qteproduit', $quantityToAdd);
            } else {
                $ligneproduit = DB::table('contenir')
                    ->where('idpanier', $panierId)
                    ->max('ligneproduit');
                $ligneproduit = ($ligneproduit === null) ? 1 : $ligneproduit + 1;

                DB::table('contenir')->insert([
                    'idproduit'   => $produitId,
                    'idpanier'    => $panierId,
                    'ligneproduit'=> $ligneproduit,
                    'qteproduit'  => $quantityToAdd,
                    'idtaille'    => $selectedSize,
                    'idcoloris'   => $selectedColor,
                ]);
            }

            $totalPrice = DB::table('contenir')
                ->join('variante_produit', function($join) {
                    $join->on('contenir.idproduit', '=', 'variante_produit.idproduit')
                        ->on('contenir.idcoloris', '=', 'variante_produit.idcoloris');
                })
                ->where('contenir.idpanier', $panierId)
                ->select(DB::raw('SUM(contenir.qteproduit * variante_produit.prixproduit) as total'))
                ->value('total');

            DB::table('panier')
                ->where('idpanier', $panierId)
                ->update(['prixpanier' => $totalPrice ?? 0]);

            return redirect()
                ->route('produits.index')
                ->with('success', 'Produit ajouté au panier avec succès !');

        } catch (\Exception $e) {
            Log::error("Erreur d'ajout au panier : " . $e->getMessage());
            return back()
                ->withErrors(['error' => "Erreur technique : " . $e->getMessage()])
                ->withInput();
        }
    }
}