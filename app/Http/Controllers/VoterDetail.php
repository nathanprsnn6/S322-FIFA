<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use Illuminate\Http\Request;

class VoterDetail extends Controller
{
    public function show($id)
    {
        // 1. On récupère le Joueur, pas le Produit. 
        // On charge 'personne' pour avoir le nom/prénom
        $joueur = Joueur::with('personne')->findOrFail($id);

        // 2. Préparation des données pour la vue
        // Ta vue attend un tableau $player avec 'bio' et 'stats', on le simule ici :
        $playerData = [
            'bio' => [
                "Ceci est la première ligne de la biographie (à remplacer par la BDD).",
                "Ceci est la deuxième ligne."
            ],
            'stats' => [
                ['label' => 'Matchs', 'value' => 12],
                ['label' => 'Buts', 'value' => 5],
                ['label' => 'Passes', 'value' => 3],
            ]
        ];

        // 3. On envoie les données à la vue
        return view('voterDetail', [
            'joueur' => $joueur,
            'player' => $playerData // On passe les données supplémentaires
        ]);
    }
}