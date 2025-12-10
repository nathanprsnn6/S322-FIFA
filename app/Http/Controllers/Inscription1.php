<?php

namespace App\Http\Controllers;

use App\Models\Nation;
use Illuminate\Http\Request;

class Inscription1 extends Controller
{
    public function index()
    {
        $nations = Nation::all(); 

        return view('inscription1', [
            'nations' => $nations 
        ]);
    }

    public function store(Request $request)
    {
        //dd($request->ville);
        $validatedData = $request->validate([
            'nom' => 'required|string|max:50',
            'prenom' => 'required|string|max:50',
            'courriel' => 'required|email|unique:utilisateur,courriel',
            'jour_naissance' => 'required',
            'mois_naissance' => 'required',
            'annee_naissance' => 'required',
            'naiss_ville' => 'required',
            'pays_naissance' => 'required',
            'pays_residence' => 'required',
            'langue' => 'required',
            'cp' => 'required|string',
            'ville' => 'required|string',
        ], [

            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    
            'courriel.required' => 'Nous avons besoin de votre email pour vous contacter.',
            'courriel.email' => 'Veuillez entrer une adresse email valide.',
            'courriel.unique' => 'Ce courriel est déjà utilisé par un autre membre.',
            
            'cp.required' => 'Le code postal est requis.',
            
            'jour_naissance.required' => 'Le jour de naissance est manquant.',
            'mois_naissance.required' => 'Le mois est requise.',
            'annee_naissance.required' => 'L\'année est requise.',
        ]);


        $dateNaissance = $request->annee_naissance . '-' . $request->mois_naissance . '-' . $request->jour_naissance;

        session()->put('infos_perso', [
            'nom' => $request->nom, 
            'prenom' => $request->prenom,
            'date_naissance' => $dateNaissance,
            'pays_naissance' => $request->pays_naissance,
            'naiss_ville' => $request->naiss_ville,
            'pays_residence' => $request->pays_residence,
            'langue' => $request->langue,
            'courriel' => $request->courriel,
            'ville' => $request->ville,
            'cp' => $request->cp,
        ]);
        

        return redirect()->route('inscription2.index');
    }
}