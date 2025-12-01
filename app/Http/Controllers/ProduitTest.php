<?php

namespace App\Http\Controllers;

use App\Models\Produit; // On n'oublie pas le modèle

class ProduitTest extends Controller
{
    public function index()
    {
        // Récupère TOUTES les lignes de la table 'personne'
        $liste = Produit::all(); 

        // Envoie la variable $liste à la vue sous le nom 'personnes'
        return view('produitTest', ['produits' => $liste]);
    }
}