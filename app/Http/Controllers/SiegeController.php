<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SiegeController extends Controller
{
    public function index()
    {
        if (Auth::user()->idrole != 7) {
            return redirect('/')->with('error', 'Accès réservé au Service Commande du Siège.');
        }

        $commandes = DB::table('commande')
            ->join('livrer', 'commande.idcommande', '=', 'livrer.idcommande')
            ->join('typelivraison', 'livrer.idtypelivraison', '=', 'typelivraison.idtypelivraison')
            ->join('personne', 'commande.idpersonne', '=', 'personne.idpersonne') 
            ->leftJoin('transaction', 'commande.idtransaction', '=', 'transaction.idtransaction') 
            ->where('typelivraison.libelletypelivraison', 'ILIKE', '%Express%') 
            ->select(
                'commande.idcommande',
                'commande.etatcommande',
                'personne.nom', 
                'personne.prenom',
                'transaction.datetransaction as date_commande',
                'livrer.datelivraison as date_livraison',
                'typelivraison.libelletypelivraison'
            )
            ->orderBy('livrer.datelivraison', 'desc')
            ->get();

        return view('siege_commandes', compact('commandes'));
    }
}