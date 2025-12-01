<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur; // On n'oublie pas le modèle
use Illuminate\Http\Request;

class InscriptionConnexion extends Controller
{
    public function index()
    {
        // On récupère tous les utilisateurs
        // "with('personne')" pré-charge les données de la table liée pour éviter de faire trop de requêtes SQL
        $lesUtilisateurs = Utilisateur::with('personne')->get();

        return view('inscriptionConnexion', ['inscriptionConnexion' => $lesUtilisateurs]);
    }

    $user = User::create([
        'idpersonne',
        'idselection',
        'courriel' => $request->courriel,
        'surnom'=> $request->surnom,
        'langue'=> $request->langue,
        'cp'=> $request->cp,
        'ville'=> $request->surnom,
        'paysresidence'=> $request->paysnaissance,
        'mdp'=> $request->conf_pwd
    ]);
}