<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Contenir;
use App\Models\Panier;
use App\Models\Produit;

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
}

