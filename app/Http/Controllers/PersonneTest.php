<?php

namespace App\Http\Controllers;

use App\Models\Personne;

class PersonneTest extends Controller
{
    public function index()
    {

        $liste = Personne::all(); 

        return view('personneTest', ['personnes' => $liste]);
    }
}