<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GhostUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $cookieName = 'guest_user_id';

        if (Auth::check()) {
            return $next($request);
        }

        $ghostUserId = $request->cookie($cookieName);

        $userExists = $ghostUserId && DB::table('utilisateur')->where('idpersonne', $ghostUserId)->exists();

        if (!$userExists) {
            $personneId = DB::table('personne')->insertGetId([
                'nom'          => 'Invité',
                'prenom'       => 'Utilisateur',
            ]);

            $email = 'ghost_' . Str::random(20) . '@example.com';

            $userId = DB::table('utilisateur')->insertGetId([
                'idpersonne' => $personneId,
                'idrole'     => 1,
            ], 'idpersonne');

            $ghostUserId = $userId;

            $cookie = cookie($cookieName, $ghostUserId, 60 * 24 * 30);

            $response = $next($request);
            return $response->cookie($cookie);
        }

        $request->merge(['ghost_user_id' => $ghostUserId]);

        return $next($request);
    }
}
