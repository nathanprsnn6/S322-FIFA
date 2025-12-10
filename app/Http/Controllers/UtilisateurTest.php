<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurTest extends Controller
{
    public function index()
    {
        $lesUtilisateurs = Utilisateur::with('personne')->get();

        return view('utilisateurTest', ['utilisateurs' => $lesUtilisateurs]);
    }
}