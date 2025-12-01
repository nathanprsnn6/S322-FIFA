<?php

namespace App\Http\Controllers;

use App\Models\Personne; // On n'oublie pas le modèle

class PersonneTest extends Controller
{
    public function index()
    {
        // Récupère TOUTES les lignes de la table 'personne'
        $liste = Personne::all(); 

        // Envoie la variable $liste à la vue sous le nom 'personnes'
        return view('personneTest', ['personnes' => $liste]);
    }
}