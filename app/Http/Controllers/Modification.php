<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Nation;
use App\Models\Categorie;

class Modification extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $nations = Nation::all();
        $categories = Categorie::all();

        return view('modification', compact('user', 'nations', 'categories'));
    }

    public function update(Request $request)
    {
        //dd($request->ville_select);
        /** @var \App\Models\User $user */
        $user = Auth::user();


        $request->validate([
            'nom' => 'nullable|string|max:50',
            'prenom' => 'nullable|string|max:50',
            'courriel' => ['required', 'email', Rule::unique('utilisateur', 'courriel')->ignore($user->idpersonne, 'idpersonne')],
            
            'jour_naissance' => 'required|numeric',
            'mois_naissance' => 'required|numeric',
            'annee_naissance' => 'required|numeric',
            
            'password' => 'nullable|min:8',
        ]);

        $personne = $user->personne; 

        if ($personne) {
            $personne->nom = $request->nom;
            $personne->prenom = $request->prenom;
            $personne->lieunaissance = $request->naiss_ville;
            
            try {
                $personne->datenaissance = Carbon::createFromDate(
                    $request->annee_naissance, 
                    $request->mois_naissance, 
                    $request->jour_naissance
                )->format('Y-m-d');
            } catch (\Exception $e) {
                return back()->withErrors(['date_naissance' => 'Date invalide.']);
            }


            $personne->save();
        }


        
        $user->courriel = $request->courriel;
        $user->surnom = $request->surnom;
        $user->ville = $request->ville_select ?? $user->ville;
        $user->cp = $request->cp;

        $user->langue_idnation = $request->langue; 
        

        $user->favori_idnation = $request->favorite; 
        

        $user->idnation = $request->pays_residence;


        $user->naiss_idnation = $request->pays_naissance;
        


        if ($request->filled('password')) {

            $user->mdp = $request->password; 
        }


        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès !');
    }

    public function delete()
    {
        $user = Auth::user();
        $personneId = Auth::id();

        
        if (!$personneId) {
            Log::error("L'id de la personne est null");
            return back()->with('error', 'Identifiant utilisateur invalide.');
        }

        DB::beginTransaction();

        try {
            $panierIds = DB::table('panier')->where('idpersonne', $personneId)->pluck('idpanier');
            if ($panierIds->isNotEmpty()) {
                DB::table('contenir')->whereIn('idpanier', $panierIds)->delete();
                Log::info("Suppression de " . $panierIds->count() . " entrées dans 'contenir' pour les paniers de l'utilisateur " . $personneId);
            }

            DB::table('commande')->where('idpersonne', $personneId)->delete();
            Log::info("Suppression des commandes de l'utilisateur " . $personneId);

            DB::table('panier')->where('idpersonne', $personneId)->delete();
            Log::info("Suppression des paniers de l'utilisateur " . $personneId);

            DB::table('commande')->where('idpersonne', $personneId)->delete();
            Log::info("Suppression des commandes de l'utilisateur " . $personneId);

            $carteBancaires = DB::table('cartebancaire')->where('idpersonne', $personneId)->pluck('idcb');

            if ($carteBancaires->isNotEmpty()) {
                foreach ($carteBancaires as $idcb) {
                    DB::table('transaction')->where('idcb', $idcb)->delete();
                    DB::table('cartebancaire')->where('idcb', $idcb)->delete();
                }
                Log::info("Suppression des cartes bancaires de l'utilisateur " . $personneId);
            } else {
                Log::info("Aucune carte bancaire à supprimer pour l'utilisateur " . $personneId);
            }

            DB::table('client')->where('idpersonne', $personneId)->delete();
            Log::info("Suppression du client lié à l'utilisateur " . $personneId);

            $user->delete();
            Log::info("Suppression de l'utilisateur (personne) " . $personneId);

            DB::commit();

            Auth::logout();

            return redirect('/')->with('status', 'Votre compte et toutes vos données personnelles ont été supprimés avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors de la suppression des données de l'utilisateur " . $personneId . ": " . $e->getMessage());

            return back()->with('error', 'Une erreur est survenue lors de la suppression de vos données. Veuillez réessayer ou contacter le support.');
        }
    }
}