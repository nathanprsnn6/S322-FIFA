<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use App\Models\Personne;
use App\Models\Utilisateur;
use App\Models\Vote; // <--- TRES IMPORTANT : On importe le Modèle
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VoterController extends Controller
{
    public function index(Request $request)
    {
        $joueurs = Joueur::all();
        return view('voter', ['joueurs' => $joueurs]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rank_1' => 'required|exists:joueur,idpersonne',
            'rank_2' => 'required|exists:joueur,idpersonne|different:rank_1',
            'rank_3' => 'required|exists:joueur,idpersonne|different:rank_1|different:rank_2',
        ], [
            'rank_1.required' => 'Vous devez choisir un joueur pour la 1ère place.',
            'rank_2.different' => 'Le joueur en 2ème place doit être différent du 1er.',
            'rank_3.different' => 'Le joueur en 3ème place doit être unique.',
        ]);

        try {
            $userId = Auth::id(); 
            
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Vous devez être connecté.');
            }

            Log::info("Début de l'enregistrement du vote pour l'user ID: " . $userId);

            // --- CORRECTION ICI ---
            // On utilise Vote:: (le Modèle) et pas Voter:: (le Contrôleur qui n'existe plus sous ce nom)
            
            // Suppression des anciens votes
            Vote::where('uti_idpersonne', $userId)->delete();

            // Vote #1
            Vote::create([
                'uti_idpersonne' => $userId,
                'idpersonne'      => $request->input('rank_1'),
                'position'     => 1
            ]);

            // Vote #2
            Vote::create([
                'uti_idpersonne' => $userId,
                'idpersonne'      => $request->input('rank_2'),
                'position'     => 2
            ]);

            // Vote #3
            Vote::create([
                'uti_idpersonne' => $userId,
                'idpersonne'      => $request->input('rank_3'),
                'position'     => 3
            ]);

            return redirect()->route('voter.index')->with('success', 'Votre vote a été pris en compte avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur lors du vote : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement.');
        }
    }
}