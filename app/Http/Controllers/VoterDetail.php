<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoterDetail extends Controller
{
    public function show($id)
    {
        $joueur = Joueur::with('personne')
            ->join('photo', 'joueur.idphotodetails', '=', 'photo.idphoto')
            ->select('joueur.*', 'photo.destinationphoto')
            ->findOrFail($id);

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

        $equipe = DB::table('equipe')
            ->join('joueur', 'equipe.idequipe', '=', 'joueur.idequipe')
            ->where('joueur.idpersonne', $id)
            ->select('equipe.libelleequipe', 'equipe.idequipe')
            ->first();

        $nation = DB::table('nation')
            ->join('joueur', 'nation.idnation', '=', 'joueur.idnation')
            ->where('joueur.idpersonne', $id)
            ->select('nation.nomnation')
            ->first();
            
        $prefill = session('vote_attente', []);

        return view('voterDetail', [
            'joueur'     => $joueur,
            'playerData' => $playerData,
            'equipe'     => $equipe,
            'nation'     => $nation
        ]);
    }
}