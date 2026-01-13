<?php

namespace App\Http\Controllers;

use App\Models\TypeVote;
use Illuminate\Http\Request;
use App\Models\Joueur;

class TypeVoteController extends Controller
{
    public function index() {
        $typesVotes = TypeVote::all();
        return view('typevote', compact('typesVotes'));
    }

    public function create() {
        return view('typevote_form'); // On crée une vue unique pour le formulaire
    }

    public function store(Request $request) {
        $request->validate([
            'nomtypevote' => 'required|string|max:255',
            'datefin' => 'required|date'
        ]);

        TypeVote::create($request->all());
        return redirect()->route('typesvote.index')->with('success', 'Type de vote ajouté !');
    }

    public function edit($id) {
        $type = TypeVote::findOrFail($id);
        return view('typevote_form', compact('type'));
    }

    public function update(Request $request, $id) {
        $type = TypeVote::findOrFail($id);
        $type->update($request->all());
        return redirect()->route('typesvote.index')->with('success', 'Type de vote mis à jour !');
    }

    public function manageJoueurs($id) {
    $type = TypeVote::findOrFail($id);
    $joueurs = Joueur::all(); // On récupère tous les joueurs
    
    // On récupère les IDs des joueurs déjà éligibles pour pré-cocher les cases
    $eligibleIds = $type->joueurs()->pluck('joueur.idpersonne')->toArray();

    return view('typevote_joueurs', compact('type', 'joueurs', 'eligibleIds'));
}

public function storeJoueurs(Request $request, $id) {
    $type = TypeVote::findOrFail($id);
    
    // La méthode sync() est magique : elle ajoute les nouveaux et retire les anciens 
    // dans la table pivot 'eligiblevote' automatiquement.
    $type->joueurs()->sync($request->input('joueurs', []));

    return redirect()->route('typesvote.index')->with('success', 'Liste des joueurs mise à jour !');
}
}