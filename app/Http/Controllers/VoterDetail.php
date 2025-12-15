<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoterDetail extends Controller
{
    public function show($id)
    {
        // --- 1. Récupération du Joueur ---
        // On construit la requête d'abord, on exécute à la fin.
        $joueur = Joueur::with('personne') // On charge la relation 'personne'
            ->join('photo', 'joueur.idphotodetails', '=', 'photo.idphoto') // Jointure sur idphotodetails
            ->select('joueur.*', 'photo.destinationphoto') // On sélectionne les champs du joueur et l'image
            ->findOrFail($id); // On récupère LE joueur par son ID (ou erreur 404 si introuvable)

        // --- 2. Statistiques ---
        $playerData = DB::table('statistiques')
            ->join('joueur', 'statistiques.idjoueur', '=', 'joueur.idpersonne')
            ->where('statistiques.idjoueur', $id)
            ->select(
                'statistiques.matchs_joues',
                'statistiques.titularisations',
                'statistiques.minutes_jouees',
                'statistiques.buts',
                'statistiques.nb_selections',
                'statistiques.premiere_selection_date',
                'statistiques.premiere_selection_adversaire',
                'statistiques.premiere_selection_score'
            )
            ->first();

        // --- 3. Équipe ---
        $equipe = DB::table('equipe')
            ->join('joueur', 'equipe.idequipe', '=', 'joueur.idequipe')
            ->where('joueur.idpersonne', $id) // On cherche l'équipe DU joueur $id
            ->select('equipe.libelleequipe', 'equipe.idequipe')
            ->first();

        // --- 4. Nation ---
        $nation = DB::table('nation')
            ->join('joueur', 'nation.idnation', '=', 'joueur.idnation')
            ->where('joueur.idpersonne', $id) // On cherche la nation DU joueur $id
            ->select('nation.nomnation')
            ->first();
            
        // Récupération de la session pour pré-remplir si besoin
        $prefill = session('vote_attente', []);

        // --- 5. Envoi à la vue ---
        return view('voterDetail', [
            'joueur'     => $joueur,
            'playerData' => $playerData,
            'equipe'     => $equipe,
            'nation'     => $nation
        ]);
    }
}