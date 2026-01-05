<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Commande extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $toutesLesCommandes = DB::table('commande')
            ->join('panier', 'commande.idpanier', '=', 'panier.idpanier')
            ->leftJoin('transaction', 'commande.idtransaction', '=', 'transaction.idtransaction')
            ->leftJoin('livrer', 'commande.idcommande', '=', 'livrer.idcommande')
            ->leftJoin('typelivraison', 'livrer.idtypelivraison', '=', 'typelivraison.idtypelivraison')
            ->where('commande.idpersonne', $userId)
            ->select(
                'commande.idcommande',
                'commande.etatcommande',
                'panier.prixpanier',
                'transaction.datetransaction',
                'typelivraison.libelletypelivraison',
                // AJOUT : Informations logistiques pour l'User Story
                'livrer.datelivraison', 
                'livrer.creneaulivraison'
            )
            ->orderBy('commande.idcommande', 'desc')
            ->get();

        $idsCommandes = $toutesLesCommandes->pluck('idcommande');

        $detailsProduits = DB::table('contenir')
            ->join('panier', 'contenir.idpanier', '=', 'panier.idpanier')
            ->join('commande', 'panier.idpanier', '=', 'commande.idpanier')
            ->join('produit', 'contenir.idproduit', '=', 'produit.idproduit')
            ->join('taille', 'contenir.idtaille', '=', 'taille.idtaille')
            ->join('coloris', 'contenir.idcoloris', '=', 'coloris.idcoloris')
            ->whereIn('commande.idcommande', $idsCommandes)
            ->select(
                'commande.idcommande',
                'contenir.qteproduit',
                'produit.titreproduit',
                'taille.tailleproduit',
                'coloris.libellecoloris'
            )
            ->get();

        foreach ($toutesLesCommandes as $commande) {
            $commande->produits = $detailsProduits->where('idcommande', $commande->idcommande);
        }

        $commandesEnCours = $toutesLesCommandes->filter(function ($commande) {
            return in_array($commande->etatcommande, ['En préparation', 'En cours de livraison']);
        });

        $commandesPassees = $toutesLesCommandes->filter(function ($commande) {
            return in_array($commande->etatcommande, ['Livrée', 'Annulée']);
        });

        return view('commande', compact('commandesEnCours', 'commandesPassees'));
    }
}