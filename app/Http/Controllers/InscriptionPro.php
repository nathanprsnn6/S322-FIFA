<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class InscriptionPro extends Controller
{
    public function create()
    {
        if (!session()->has('idpersonne')) {
            return redirect('/inscription')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        return view('inscriptionPro');
    }

public function store(Request $request)
    {
        $validated = $request->validate([
            'nomsociete' => 'required|string|max:100',
            'tva' => [
                'required',
                'string',
                'max:20',
                'unique:professionel,tva',
                'regex:/^[A-Z]{2}[0-9A-Z]{2,15}$/'], 
            'activite'   => 'required|string|max:255',
        ],[

            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            'unique' => 'Le champ :attribute existe déjà.',
            'regex'    => 'Le format du TVA est invalide (Ex: FR123456789).',
        ]);

        $sessionData = session('idpersonne');


        if (is_array($sessionData)) {

            $vraiId = $sessionData['idpersonne'] ?? $sessionData['id'] ?? null;
        } else {

            $vraiId = $sessionData;
        }


        if (!$vraiId) {
            return redirect('/login')->with('error', 'Session invalide. Reconnectez-vous.');
        }


        $tvaClean = str_replace(' ', '', $validated['tva']);


        try {
            DB::table('professionel')->insert([
                'idpersonne' => $vraiId,
                'nomsociete' => $validated['nomsociete'],
                'tva'        => $tvaClean, 
                'activite'   => $validated['activite'],
            ]);

            return redirect('/')->with('success', 'Compte pro activé !');

        } catch (\Exception $e) {

            dd($e->getMessage());
        }
    }
}