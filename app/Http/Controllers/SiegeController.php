<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiegeController extends Controller
{
    public function index()
    {
        if (Auth::user()->idrole != 7) {
            return redirect('/')->with('error', 'Accès réservé au Service Commande du Siège.');
        }

        $dateLimite = Carbon::now()->subDays(15);
        
        $lignesMisesAJour = DB::table('commande')
            ->where('etatcommande', 'Sous réserve')
            ->where('datereserve', '<=', $dateLimite)
            ->update(['etatcommande' => 'Livrée']);

        $messageAuto = null;
        if ($lignesMisesAJour > 0) {
            $messageAuto = "$lignesMisesAJour commande(s) sous réserve depuis +15 jours ont été clôturées automatiquement.";
        }

        $commandes = DB::table('commande')
            ->join('livrer', 'commande.idcommande', '=', 'livrer.idcommande')
            ->join('typelivraison', 'livrer.idtypelivraison', '=', 'typelivraison.idtypelivraison')
            ->join('personne', 'commande.idpersonne', '=', 'personne.idpersonne') 
            ->leftJoin('transaction', 'commande.idtransaction', '=', 'transaction.idtransaction') 
            ->select(
                'commande.idcommande',
                'commande.etatcommande',
                'commande.datereserve',
                'personne.nom', 
                'personne.prenom',
                'transaction.datetransaction as date_commande',
                'livrer.datelivraison as date_livraison',
                'typelivraison.libelletypelivraison'
            )
            ->orderBy('livrer.datelivraison', 'desc')
            ->get();

        $typesLivraison = DB::table('typelivraison')->distinct()->pluck('libelletypelivraison');

        return view('siege_commandes', compact('commandes', 'typesLivraison', 'messageAuto'));
    }

    public function changerEtatLivraison(Request $request, $id)
    {
        if (Auth::user()->idrole != 7) {
            return redirect()->back()->with('error', 'Non autorisé.');
        }

        $request->validate([
            'action' => 'required|in:accepter,refuser,reserve'
        ]);

        $data = [];
        $message = '';

        if ($request->action === 'reserve') {
            $data = [
                'etatcommande' => 'Sous réserve',
                'datereserve' => Carbon::now()
            ];
            $message = 'Commande mise en réserve pour suivi.';
        } elseif ($request->action === 'accepter') {
            $data = ['etatcommande' => 'Livrée'];
            $message = 'Livraison acceptée par le client. Commande clôturée.';
        } else {
            $data = ['etatcommande' => 'Annulée'];
            $message = 'Refus du client accepté. Commande annulée.';
        }

        DB::table('commande')
            ->where('idcommande', $id)
            ->update($data);

        return redirect()->route('siege.index')->with('success', $message);
    }
}