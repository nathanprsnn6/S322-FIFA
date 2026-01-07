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
        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            $userId = session('guest_user_id');
            if (!$userId) {
                return ['contenirs' => collect(), 'prixpanier' => 0];
            }
        }

        $panier = Panier::where('idpersonne', $userId)->first();

        if (!$panier) {
            return ['contenirs' => collect(), 'prixpanier' => 0];
        }

        $contenirs = Contenir::where('idpanier', $panier->idpanier)
            ->with(['produit.illustrer', 'variante'])
            ->get();

        $prixpanier = 0;

        $contenirs->each(function ($item) use (&$prixpanier) {
            $prixUnitaire = $item->variante->prixproduit ?? 0;
            $item->prixLigne = $item->qteproduit * $prixUnitaire;
            $prixpanier += $item->prixLigne;
        });

        return [
            'contenirs' => $contenirs,
            'prixpanier' => $prixpanier,
        ];
    }    

    public function updateQuantity(Request $request, $compositeId) 
    {
        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            $userId = session('guest_user_id');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->input('quantity');
        
        $ids = explode('-', $compositeId);
        
        if (count($ids) !== 3) {
            return response()->json(['message' => 'Format d\'identifiant de ligne invalide.'], 400);
        }

        list($productId, $colorId, $tailleId) = $ids;
        
        $panier = Panier::where('idpersonne', $userId)->first(); 
        
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
            'new_item_price' => number_format($nouveauPrixLigne, 2, ',', ' '), 
            'new_total_price' => number_format($nouveauTotalPanier, 2, ',', ' ')
        ]);
    }

    public function removeItem($compositeId) 
    {
        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            $userId = session('guest_user_id');
        }

        $ids = explode('-', $compositeId);
        
        if (count($ids) !== 3) {
            return response()->json(['message' => 'Format d\'identifiant de ligne invalide.'], 400);
        }

        list($productId, $colorId, $tailleId) = $ids;
        
        $panier = Panier::where('idpersonne', $userId)->first(); 
        
        
        
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

