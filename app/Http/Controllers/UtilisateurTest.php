<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur; // On n'oublie pas le modèle
use Illuminate\Http\Request;

class UtilisateurTest extends Controller
{
    public function index()
    {
        // On récupère tous les utilisateurs
        // "with('personne')" pré-charge les données de la table liée pour éviter de faire trop de requêtes SQL
        $lesUtilisateurs = Utilisateur::with('personne')->get();

        return view('utilisateurTest', ['utilisateurs' => $lesUtilisateurs]);
    }
}