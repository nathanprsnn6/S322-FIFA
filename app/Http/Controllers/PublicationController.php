<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicationController extends Controller 
{
    // Affiche la liste de toutes les publications
public function index(Request $request) {
    $search = $request->input('search');

    $publications = Publication::with(['photo', 'blog', 'article']) 
        ->when($search, function ($query, $search) {
            return $query->where('titrepublication', 'like', "%{$search}%");
        })
        ->orderBy('datepublication', 'desc')
        ->get();

    return view('publication', compact('publications'));
}

public function show($id) {
    // On charge tout d'un coup
    $publication = Publication::with(['photo', 'blog.commentaires.personne', 'article'])
        ->where('idpublication', $id)
        ->firstOrFail();

    return view('publication_detail', compact('publication'));
}
}