<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Personne;
use App\Models\Utilisateur;
use App\Models\CarteBancaire;
use App\Models\Client;
use App\Models\Panier; 
use App\Models\Contenir;
use App\Models\Commande;

class DpdController extends Controller{
    public function index()
    {
        $users = Personne::query()
              ->join('utilisateur','utilisateur.idpersonne','=','personne.idpersonne')
              ->where('utilisateur.last_login_date','<', now()->subYears(2))
              ->select('personne.idpersonne','personne.nom','personne.prenom','utilisateur.last_login_date')
              ->orderBy('utilisateur.last_login_date','asc')
              ->get();

        return view('dpd', ['users' => $users]);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $panierIds = Panier::where('idpersonne', $id)->pluck('idpanier');
        try {
            

            $personne = Personne::where('idpersonne', $id)->first();
            $utilisateur = Utilisateur::where('idpersonne', $id)->first();
            $client = Client::where('idpersonne', $id)->first();
            $cartbancaire = CarteBancaire::where('idpersonne', $id)->first();
            $panier = Panier::where('idpersonne', $id);
            $commande = Commande::where('idpersonne', $id);
            

            if ($client !== null) {
                $client->delete();
                Log::info('Utilisateur : ' . $id . ' information suprimmer de la table client avec succes');
            }
            if ($cartbancaire !== null) {
                $cartbancaire->delete();
                Log::info('Utilisateur : ' . $id . ' information suprimmer de la table cartbancaire avec succes');
            }
            if ($panierIds !== null) {
                Contenir::whereIn('idpanier', $panierIds)->delete();
                Log::info('Utilisateur : ' . $id . ' information suprimmer de la table contenir avec succes');
            }
            if ($panier !== null) {
                $panier->delete();
                Log::info('Utilisateur : ' . $id . ' information suprimmer de la table panier avec succes');
            }
            if ($commande !== null) {
                $commande->delete();
                Log::info('Utilisateur : ' . $id . ' information suprimmer de la table commande avec succes');
            }
            
            $utilisateur->delete();
            $personne->delete();


            DB::commit();
            return redirect()->route('dpd.index')->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur suppression utilisateur ID '.$id.': '.$e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression de l\'utilisateur. Veuillez réessayer.'])->withInput();
        }
    }
}