<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Connexion extends Controller
{
    // Affiche la vue "connexion.blade.php"
    public function show()
    {
        // Correction ici : on appelle directement 'connexion'
        return view('connexion');
    }

    // Traite le formulaire
    public function login(Request $request)
    {
        // 1. Validation des champs du formulaire
        $credentials = $request->validate([
            'courriel' => ['required', 'email'],
            'mdp'      => ['required'],
        ]);

        // 2. Tentative de connexion
        // IMPORTANT : On passe 'password' comme clé à Auth::attempt pour la vérification du hachage
        // même si la valeur vient de $request->mdp et que la colonne en BDD est 'mdp'.
        $authAttempt = Auth::attempt([
            'courriel' => $request->courriel, 
            'password' => $request->mdp       
        ], $request->filled('remember'));

        if ($authAttempt) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Connexion réussie.');
        }

        // 3. Echec
        return back()->withErrors([
            'courriel' => 'Identifiants incorrects.',
        ])->onlyInput('courriel');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Déconnexion réussie.');
    }
}