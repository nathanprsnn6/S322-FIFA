<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;

class IdentifierVisiteur
{
    public function handle(Request $request, Closure $next)
    {
        $cookieName = 'user_id_visiteur';

        if (!$request->user()) {
            $guestUserId = $request->cookie($cookieName);

            if (!$guestUserId) {
                $guestUserId = (string) Str::uuid();

                $response = $next($request);

                $cookie = cookie($cookieName, $guestUserId, 60 * 24 * 30);

                return $response->cookie($cookie);
            } else {
                return $next($request);
            }
        }
        return $next($request);
    }
}
