<?php

namespace App\Http\Controllers;

use App\Models\Nation;
use Illuminate\Http\Request;

class Inscription2 extends Controller
{
    public function index()
    {

        if (!session()->has('infos_perso')) {
            return redirect()->route('inscription1.index');
        }


        $nations = Nation::all(); 

        return view('inscription2', [
            'nations' => $nations
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:30|unique:utilisateur,surnom',
            'favorite' => 'nullable|integer', 
        ],[
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'integer' => 'Le champ :attribute doit être un entier.',
            'unique' => 'Le champ :attribute est déja utilisé par un autre utilisateur.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
        ]
    );


        session()->put('infos_profil', [
            'surnom' => $request->nickname,
            'favori_idnation' => $request->favorite, 
        ]);

        return redirect()->route('inscription3.index');
    }
}