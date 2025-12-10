<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            
            // Reconstitution de la date
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
}