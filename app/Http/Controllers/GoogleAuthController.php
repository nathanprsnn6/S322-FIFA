<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

public function callback()
{
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();
    } catch (\Exception $e) {
        // CORRECTION : Le nom dans web.php est 'login', pas 'connexion'
        return redirect()->route('login')->with('error', 'Erreur lors de la connexion Google.');
    }

    $user = User::where('google_id', $googleUser->id)
                ->orWhere('courriel', $googleUser->email)
                ->first();

    if ($user) {
        if (!$user->google_id) {
            $user->update(['google_id' => $googleUser->id]);
        }

        Auth::login($user);

        // CORRECTION : 'dashboard' n'existe pas, on utilise 'home' (qu'on vient de nommer)
        // ou 'produits.index' selon votre choix.
        return redirect()->route('welcome');

    } else {
        // ... reste du code (celui-ci est correct car 'inscription1.index' existe bien)
        session(['google_data' => [
            'courriel' => $googleUser->email,
            'nom' => $googleUser->user['family_name'] ?? $googleUser->name,
            'prenom' => $googleUser->user['given_name'] ?? '',
            'google_id' => $googleUser->id,
        ]]);

        return redirect()->route('inscription1.index');
    }
}
}