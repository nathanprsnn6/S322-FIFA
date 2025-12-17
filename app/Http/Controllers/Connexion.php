<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Connexion extends Controller
{
    public function show()
    {
        return view('connexion');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'courriel' => ['required', 'email'],
            'mdp'      => ['required'],
        ]);
        $authAttempt = Auth::attempt([
            'courriel' => $request->courriel, 
            'password' => $request->mdp       
        ], $request->filled('remember'));

        if ($authAttempt) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Connexion réussie.');
        }
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