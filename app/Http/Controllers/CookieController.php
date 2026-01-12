<?php
namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use App\Models\Nation;


class CookieController extends Controller{
    public function saveConsent(Request $request){
        // Récupère les préférences depuis le formulaire (par exemple, un tableau JSON)
        $preferences = [
            'analytics' => $request->input('analytics') === 'true', // Ex: Google Analytics
            'marketing' => $request->input('marketing') === 'true', // Ex: Retargeting
        ];
        
        // Le cookie de consentement lui-même
        $consentCookie = cookie(
            'user_consent',              // Nom du cookie
            json_encode($preferences),   // La valeur (JSON des choix)
            60 * 24 * 365,               // Durée de vie (1 an)
            null,                        // Chemin
            null,                        // Domaine
            true,                        // Secure (HTTPS uniquement)
            false                        // HttpOnly (doit être accessible par JS pour la bannière)
        );

        // Retourne une réponse simple avec le cookie attaché
        return response()->json(['status' => 'success'])->cookie($consentCookie);
    }
}

