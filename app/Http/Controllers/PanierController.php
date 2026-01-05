<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Contenir;
use App\Models\Panier;

class PanierController extends Controller
{    
    public function getCartItems()
    {
        $contenirs = Contenir::all();
        $id = $contenirs->isNotEmpty() ? $contenirs->first()->idproduit : null;
        if (Auth::check()) {
            
            $panier = Panier::where('idpersonne', Auth::id())->first();
            $photo = 
            DB::table('photo')
            ->join('illustrer', 'photo.idphoto', '=', 'illustrer.idphoto')
            ->where('illustrer.idproduit', $id)
            ->select('photo.destinationphoto')
            ->first();

            if ($panier) {               
                $contenirs = Contenir::where('idpanier', $panier->idpanier)
                                        ->with(['produit.illustrer', 'variante']) 
                                        ->get();
                                        
                $prixpanier = 0; 

                $contenirs->each(function ($item) use (&$prixpanier) {
                    
                    
                    $prixUnitaire = $item->variante->prixproduit 
                                    ?? 0;
                                    
                    $item->prixLigne = $item->qteproduit * $prixUnitaire;
                    $prixpanier += $item->prixLigne;
                });
                
                return [
                    'contenirs' => $contenirs,
                    'prixpanier' => $prixpanier 
                ];
            }
        }        

        return ['contenirs' => collect(), 'prixpanier' => 0];
        return view('contenir', compact('idproduit', 'idcoloris', 'idtaille', 'ligneproduit','qteproduit', 'photo'));
    }    

    public function updateQuantity(Request $request, $compositeId) 
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity');
        
        $ids = explode('-', $compositeId);
        
        if (count($ids) !== 3) {
            return response()->json(['message' => 'Format d\'identifiant de ligne invalide.'], 400);
        }

        list($productId, $colorId, $tailleId) = $ids;
        
        if (!Auth::check()) {
             return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }
        
        $panier = Panier::where('idpersonne', Auth::id())->first(); 
        
        if (!$panier) {
             return response()->json(['message' => 'Panier introuvable pour cet utilisateur.'], 404);
        }
        
        $lignePanier = Contenir::where('idpanier', $panier->idpanier)
                               ->where('idproduit', $productId)
                               ->where('idcoloris', $colorId)
                               ->where('idtaille', $tailleId)
                               ->first();

        if (!$lignePanier) {
            return response()->json(['message' => 'Ligne de panier introuvable.'], 404);
        }

        Contenir::where('idpanier', $panier->idpanier)
                ->where('idproduit', $productId)
                ->where('idcoloris', $colorId)
                ->where('idtaille', $tailleId)
                ->update([
                    'qteproduit' => $quantity
                ]);
        
        $prixUnitaire = $lignePanier->getPrixUnitaireAttribute(); 
        $nouveauPrixLigne = $quantity * $prixUnitaire;
        
        $nouveauTotalPanier = 0;
        
        $contenirsMisAJour = Contenir::where('idpanier', $panier->idpanier)->get();
        
        $contenirsMisAJour->each(function ($item) use (&$nouveauTotalPanier) {
            $prixUnitaireItem = $item->getPrixUnitaireAttribute();
            $prixLigneCalcule = $item->qteproduit * $prixUnitaireItem;
            
            $nouveauTotalPanier += $prixLigneCalcule;
        });
        
        return response()->json([
            'success' => true,
            // Utiliser le nouveau prix de ligne que l'on a calculé
            'new_item_price' => number_format($nouveauPrixLigne, 2, ',', ' '), 
            'new_total_price' => number_format($nouveauTotalPanier, 2, ',', ' ')
        ]);
    }

    public function removeItem($compositeId) 
    {
        $ids = explode('-', $compositeId);
        
        if (count($ids) !== 3) {
            return response()->json(['message' => 'Format d\'identifiant de ligne invalide.'], 400);
        }

        list($productId, $colorId, $tailleId) = $ids;
        
        if (!Auth::check()) {
             return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }
        
        $panier = Panier::where('idpersonne', Auth::id())->first(); 
        
        if (!$panier) {
             return response()->json(['message' => 'Panier introuvable pour cet utilisateur.'], 404);
        }
        
        $deletedCount = Contenir::where('idpanier', $panier->idpanier)
                                ->where('idproduit', $productId)
                                ->where('idcoloris', $colorId)
                                ->where('idtaille', $tailleId)
                                ->delete();

        if ($deletedCount === 0) {
            return response()->json(['message' => 'Ligne de panier introuvable.'], 404);
        }

        $nouveauTotalPanier = $this->recalculateCartTotal($panier->idpanier);
        
        return response()->json([
            'success' => true,
            'new_total_price' => number_format($nouveauTotalPanier, 2, ',', ' '),
            'removed_composite_id' => $compositeId
        ]);
    }

    protected function recalculateCartTotal($panierId)
    {
        $contenirsMisAJour = Contenir::where('idpanier', $panierId)->get();
        $nouveauTotalPanier = 0;
        
        $contenirsMisAJour->each(function ($item) use (&$nouveauTotalPanier) {
            $prixUnitaireItem = $item->getPrixUnitaireAttribute();
            $prixLigneCalcule = $item->qteproduit * $prixUnitaireItem;
            $nouveauTotalPanier += $prixLigneCalcule;
        });
        
        return $nouveauTotalPanier;
    }
}

