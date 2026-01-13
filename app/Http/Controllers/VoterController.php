<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VoterController extends Controller
{
public function index(Request $request)
{
    $userId = Auth::id(); 

    // Récupération de tous les types de votes pour l'affichage des onglets/radios
    $typevotes = DB::table('typevote')->select('idtypevote', 'nomtypevote')->get();

    // Détermination du type sélectionné (par défaut le premier de la liste)
    $selectedType = $request->query('idtypevote', $typevotes->first()->idtypevote ?? 1);

    // Requête des joueurs filtrée par éligibilité
    $joueurs = Joueur::join('photo', 'joueur.idphotovote', '=', 'photo.idphoto')
        // JOINTURE CRUCIALE : On lie le joueur à sa table d'éligibilité
        ->join('eligiblevote', 'joueur.idpersonne', '=', 'eligiblevote.idpersonne')
        // On filtre par le type de vote sélectionné
        ->where('eligiblevote.idtypevote', '=', $selectedType)
        // On garde ta logique pour afficher les votes déjà effectués par l'utilisateur
        ->leftJoin('voter', function($join) use ($userId, $selectedType) {
            $join->on('joueur.idpersonne', '=', 'voter.idpersonne')
                 ->where('voter.uti_idpersonne', '=', $userId)
                 ->where('voter.idtypevote', '=', $selectedType);
        })
        ->select(
            'joueur.*', 
            'photo.destinationphoto', 
            'voter.position as ma_position'
        )
        ->get();

    $prefill = session('vote_attente', []);

    return view('voter', compact('joueurs', 'typevotes', 'prefill', 'selectedType'));
}

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'idtypevote' => 'required|exists:typevote,idtypevote',
                'rank_1'     => 'required|exists:joueur,idpersonne',
                'rank_2'     => 'required|exists:joueur,idpersonne|different:rank_1',
                'rank_3'     => 'required|exists:joueur,idpersonne|different:rank_1|different:rank_2',
            ], [
                'idtypevote.required' => 'Veuillez sélectionner un type de vote.',
                'rank_1.required'     => 'Vous devez choisir un joueur pour la 1ère place.',
                'rank_2.different'    => 'Le joueur en 2ème place doit être différent du 1er.',
                'rank_3.different'    => 'Le joueur en 3ème place doit être unique.',
            ]);

            if (!Auth::check()) {
                session(['vote_attente' => $request->only(['idtypevote', 'rank_1', 'rank_2', 'rank_3'])]);

                return redirect()->route('login')
                    ->with('warning', 'Veuillez vous connecter pour valider votre vote. Vos choix ont été conservés.');
            }

            $userId = Auth::id();
            $typeVoteId = $request->input('idtypevote');

            Log::info("Vote User: $userId pour le Type: $typeVoteId");

            Vote::where('uti_idpersonne', $userId)
                ->where('idtypevote', $typeVoteId)
                ->delete();

            $votesData = [
                ['rank' => $request->input('rank_1'), 'pos' => 1],
                ['rank' => $request->input('rank_2'), 'pos' => 2],
                ['rank' => $request->input('rank_3'), 'pos' => 3],
            ];

            foreach ($votesData as $v) {
                Vote::create([
                    'uti_idpersonne' => $userId,
                    'idpersonne'     => $v['rank'],
                    'position'       => $v['pos'],
                    'idtypevote'     => $typeVoteId
                ]);
            }

            session()->forget('vote_attente');

            return redirect()->route('voter.index')->with('success', 'Votre vote a été enregistré !');

        } catch (\Exception $e) {
            Log::error('Erreur vote : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur technique est survenue.');
        }
    }
}