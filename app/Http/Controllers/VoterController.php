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
        // ON MODIFIE CECI : On fait le JOIN directement ici pour avoir les joueurs ET leurs photos
        $joueurs = Joueur::join('photo', 'joueur.idphotovote', '=', 'photo.idphoto')
        ->select('joueur.*', 'photo.destinationphoto') // On prend tout du joueur + le chemin de la photo
        ->get();

        $typevotes = DB::table('typevote')
        ->select('idtypevote', 'nomtypevote')
        ->get();

        // SUPPRIME tout le bloc DB::table('photo')... qui était ici, il ne sert plus à rien.

        $prefill = session('vote_attente', []);

        return view('voter', compact('joueurs', 'typevotes', 'prefill'));
    }

    public function store(Request $request)
    {
        try {
            // 2. Validation (On valide AVANT de vérifier l'auth pour s'assurer que les données sont cohérentes)
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

            // 3. Gestion du cas "Non Connecté"
            if (!Auth::check()) {
                // On sauvegarde les données validées dans la session
                session(['vote_attente' => $request->only(['idtypevote', 'rank_1', 'rank_2', 'rank_3'])]);

                // On redirige vers le login avec un message
                // Note : On utilise redirect()->guest() pour que Laravel se souvienne 
                // que l'utilisateur voulait aller quelque part (optionnel mais pratique)
                return redirect()->route('login')
                    ->with('warning', 'Veuillez vous connecter pour valider votre vote. Vos choix ont été conservés.');
            }

            // --- L'utilisateur est connecté ici ---
            $userId = Auth::id();
            $typeVoteId = $request->input('idtypevote');

            Log::info("Vote User: $userId pour le Type: $typeVoteId");

            // Suppression des anciens votes de ce type
            Vote::where('uti_idpersonne', $userId)
                ->where('idtypevote', $typeVoteId)
                ->delete();

            // Préparation des données
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

            // 4. Important : On nettoie la session après le vote réussi
            // Au cas où il y avait des données en attente, on les supprime pour repartir à zéro
            session()->forget('vote_attente');

            return redirect()->route('voter.index')->with('success', 'Votre vote a été enregistré !');

        } catch (\Exception $e) {
            Log::error('Erreur vote : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur technique est survenue.');
        }
    }
}