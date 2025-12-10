<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Inscription4 extends Controller
{
        public function index()
    {
        if (!session()->has('idpersonne') || !session()->has('idpersonne')) {
            return redirect()->route('inscription3.index')
                             ->with('error', 'Session expirée, merci de recommencer.');
        }
        return view('inscription4');
    }

    public function show()
    {

        return view('inscription.success');
    }
}