<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commentaire;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    public function store(Request $request, $id)
    {
        // 1. Validation
        $request->validate([
            'textecommentaire' => 'required|max:1000',
        ]);

        // 2. Enregistrement
        $commentaire = new Commentaire();
        $commentaire->idpublication = $id;
        
        // On récupère l'ID de la personne connectée
        // Vérifie si ta colonne s'appelle idpersonne ou user_id
        $commentaire->idpersonne = Auth::id(); 
        
        $commentaire->textecommentaire = $request->textecommentaire;
        
        // Si tu n'as pas de timestamps automatiques, ajoute la date manuellement :
        // $commentaire->datecommentaire = now(); 
        
        $commentaire->save();

        // 3. Retour à la page de l'article avec un message de succès
        return back()->with('success', 'Votre commentaire a été publié !');
    }
}