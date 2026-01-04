<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller {
    public function index(Request $request) {
        $search = $request->input('search');

        // On ajoute with('photo') pour charger la relation
        $publication = Publication::with('photo') 
            ->when($search, function ($query, $search) {
                return $query->where('titrepublication', 'like', "%{$search}%")
                             ->orWhere('resumepublication', 'like', "%{$search}%");
            })
            ->get();

        return view('publication', compact('publication'));
    }
}