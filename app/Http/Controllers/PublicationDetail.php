<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicationDetail extends Controller
{
    public function show($id)
    {
        // On récupère la publication avec TOUTES ses relations d'un coup
        // Cela inclut la photo, l'article, et le blog avec ses commentaires et leurs auteurs
        $publication = Publication::with(['photo', 'article', 'blog.commentaires.personne'])
            ->findOrFail($id);
//             $exists = DB::table('blog')->where('idpublication', $id)->exists();
// dd($id);
//         dd($publication);

        return view('publicationdetail', [
            'publication' => $publication,
            'article'     => $publication->article, // Sera null si c'est un blog pur
            'blog'        => $publication->blog      // Sera null si c'est un article pur
        ]);
    }

    // Fonction pour enregistrer le commentaire
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'texte' => 'required|min:2'
        ]);

        if (!Auth::check()) {
            return back()->with('error', 'Vous devez être connecté pour commenter.');
        }

        Commentaire::create([
            'idpublication'    => $id,
            'idpersonne'       => Auth::id(),
            'textecommentaire' => $request->texte
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }
}