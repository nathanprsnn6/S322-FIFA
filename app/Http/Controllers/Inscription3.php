<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Personne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class Inscription3 extends Controller
{
    public function index()
    {
        if (!session()->has('infos_perso') || !session()->has('infos_profil')) {
            return redirect()->route('inscription1.index')
                             ->with('error', 'Session expirée, merci de recommencer.');
        }
        return view('inscription3');
    }

    public function store(Request $request)
    {

        //dd(session()->all());
        $request->validate([
            'mdp' => 'required|min:8',
            'conf_pwd' => 'required|same:mdp'
        ],[

            'required' => 'Le champ :attribute est obligatoire.',
            'min' => 'Le mot de passe doit être au minimum de :min caractères.',
            'same' => 'Les deux mot de passe ne sont pas identitique.'
        ]);

        $infosPerso = session('infos_perso');
        $infosProfil = session('infos_profil');

        try {
            DB::transaction(function () use ($request, $infosPerso, $infosProfil) {
                
                Log::info('Tentative de création Personne...');

                $nouvellePersonne = Personne::create([
                    'nom' => $infosPerso['nom'],
                    'prenom' => $infosPerso['prenom'],
                    'datenaissance' => $infosPerso['date_naissance'],
                    'lieunaissance' => $infosPerso['naiss_ville'],
                ]);
                session()->put('idpersonne',[
                    'idpersonne'=> $nouvellePersonne->idpersonne
                ]);


                Log::info('Personne créée. ID récupéré : ' . $nouvellePersonne->idpersonne);

                if (empty($nouvellePersonne->idpersonne)) {
                    throw new \Exception("Impossible de récupérer l'ID de la personne créée.");
                }

                Utilisateur::create([
                    'idpersonne'      => $nouvellePersonne->idpersonne, 
                    'idnation'        => $infosPerso['pays_residence'] ?? null,
                    'favori_idnation' => $infosProfil['favori_idnation'] ?? null,
                    'langue_idnation' => $infosPerso['langue'] ?? null,
                    'naiss_idnation'  => $infosPerso['pays_naissance'] ?? null,
                    'ville'           => $infosPerso['ville'] ?? null,
                    'cp'              => $infosPerso['cp'] ?? null,
                    'courriel'        => $infosPerso['courriel'],
                    'surnom'          => $infosProfil['surnom'],
                    'mdp'             => Hash::make($request->mdp),
                ]);

                Log::info('Utilisateur créé avec succès.');
            });


            session()->forget(['infos_perso', 'infos_profil']);

            return redirect()->route('inscription4.index')->with('success', 'Inscription validée avec succès !');
        } catch (\Exception $e) {
            Log::error("Erreur Inscription : " . $e->getMessage());
            return back()->withErrors(['error' => "Erreur technique : " . $e->getMessage()])->withInput();
        }
    }
}