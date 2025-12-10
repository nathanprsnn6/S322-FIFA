<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use App\Models\Personne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Voter extends Controller
{
    public function index(Request $request)
    {
        $joueurs = Joueur::all();
        return view('voter', ['joueurs' => $joueurs]);
    }
}