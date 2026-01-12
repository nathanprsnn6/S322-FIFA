<?php
namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class CheckCookieConsent
{
    public function handle(Request $request, Closure $next)
    {
        // Récupère la valeur du cookie de consentement
        $consent = json_decode($request->cookie('user_consent', '{}'), true);

        // Stocke les préférences dans la session ou un service conteneur
        // pour qu'elles soient disponibles partout (dans les vues, par exemple)
        $request->attributes->set('cookie_preferences', $consent);

        return $next($request);
    }
}