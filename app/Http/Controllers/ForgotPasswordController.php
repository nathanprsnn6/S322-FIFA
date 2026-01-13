<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utilisateur;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('forgotpassword'); // Cherche resources/views/forgotpassword.blade.php
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['courriel' => 'required|email']);

        $utilisateur = Utilisateur::where('courriel', $request->courriel)->first();

        if (!$utilisateur) {
            return back()->withErrors(['courriel' => "Désolé, ce joueur n'est pas dans notre base."]);
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->courriel],
            [
                'token' => bcrypt($token),
                'created_at' => now()
            ]
        );

        try {
            Mail::to($utilisateur->courriel)->send(new ResetPasswordEmail($token, $utilisateur));
        } catch (\Exception $e) {
            return back()->withErrors(['courriel' => "Erreur d'envoi : " . $e->getMessage()]);
        }

        return back()->with(['status' => "Le staff FIFA vous a envoyé un lien de récupération !"]);
    }

    // AJOUT DE LA MÉTHODE MANQUANTE
    public function showResetForm(Request $request, $token)
    {
        // Cherche resources/views/reset-password.blade.php
        return view('reset-password', [
            'token' => $token,
            'courriel' => $request->courriel
        ]);
    }

public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'courriel' => 'required|email',
        'password' => 'required|min:8|confirmed', // On garde 'password' pour le nom du champ formulaire
    ]);

    $record = DB::table('password_reset_tokens')
        ->where('email', $request->courriel)
        ->first();

    if (!$record || !password_verify($request->token, $record->token)) {
        return back()->withErrors(['courriel' => "Le lien est invalide."]);
    }

    $utilisateur = Utilisateur::where('courriel', $request->courriel)->first();

    if ($utilisateur) {
        // --- ON CHANGEpassword PAR mdp ICI ---
        $utilisateur->mdp = Hash::make($request->password); 
        $utilisateur->save();

        DB::table('password_reset_tokens')->where('email', $request->courriel)->delete();

        return redirect()->route('login')->with('status', 'Mot de passe FIFA mis à jour !');
    }

    return back()->withErrors(['courriel' => "Erreur de mise à jour."]);
}
}