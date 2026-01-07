<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationDetail extends Controller
{
    public function show($id)
    {
        // 1. On récupère la publication avec sa photo (Eager Loading)
        // On utilise with('photo') pour que l'objet photo soit inclus
        $publication = Publication::with('photo')->findOrFail($id);

        // 2. On récupère le texte long dans la table 'article' 
        $article = DB::table('article')->where('idpublication', $id)->first();

        // 3. On envoie le tout à la vue
        return view('publicationdetail', compact('publication', 'article'));
    }
}