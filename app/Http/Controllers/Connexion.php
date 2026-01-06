<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

            // Appel de la migration du panier invité vers l'utilisateur connecté
            $this->migrateGuestCartToUser(Auth::id());

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

    /**
     * Migration du panier invité vers l'utilisateur connecté
     */
    protected function migrateGuestCartToUser(int $userId)
    {
        $guestUserId = session('guest_user_id');

        if (!$guestUserId) {
            // Pas de panier invité à migrer
            return;
        }

        $limiteDate = Carbon::now()->subDays(7);
        $guestCart = DB::table('panier')
            ->where('idpersonne', $guestUserId)
            ->where('datecreationpanier', '>=', $limiteDate)
            ->first();

        if (!$guestCart) {
            // Pas de panier invité actif
            session()->forget('guest_user_id');
            return;
        }

        $userCart = DB::table('panier')
            ->where('idpersonne', $userId)
            ->where('datecreationpanier', '>=', $limiteDate)
            ->first();

        if ($userCart) {
            $guestCartItems = DB::table('contenir')
                ->where('idpanier', $guestCart->idpanier)
                ->get();

            foreach ($guestCartItems as $item) {
                $existingItem = DB::table('contenir')
                    ->where('idpanier', $userCart->idpanier)
                    ->where('idproduit', $item->idproduit)
                    ->where('idtaille', $item->idtaille)
                    ->where('idcoloris', $item->idcoloris)
                    ->first();

                if ($existingItem) {
                    DB::table('contenir')
                        ->where('idpanier', $userCart->idpanier)
                        ->where('idproduit', $item->idproduit)
                        ->where('idtaille', $item->idtaille)
                        ->where('idcoloris', $item->idcoloris)
                        ->increment('qteproduit', $item->qteproduit);
                } else {
                    $maxLigne = DB::table('contenir')
                        ->where('idpanier', $userCart->idpanier)
                        ->max('ligneproduit');
                    $newLigne = ($maxLigne === null) ? 1 : $maxLigne + 1;

                    DB::table('contenir')->insert([
                        'idpanier' => $userCart->idpanier,
                        'idproduit' => $item->idproduit,
                        'idtaille' => $item->idtaille,
                        'idcoloris' => $item->idcoloris,
                        'qteproduit' => $item->qteproduit,
                        'ligneproduit' => $newLigne,
                    ]);
                }
            }

            DB::table('contenir')->where('idpanier', $guestCart->idpanier)->delete();
            DB::table('panier')->where('idpanier', $guestCart->idpanier)->delete();

        } else {
            DB::table('panier')
                ->where('idpanier', $guestCart->idpanier)
                ->update(['idpersonne' => $userId]);
        }

        $panierId = $userCart ? $userCart->idpanier : $guestCart->idpanier;

        $totalPrice = DB::table('contenir')
            ->join('variante_produit', function($join) {
                $join->on('contenir.idproduit', '=', 'variante_produit.idproduit')
                    ->on('contenir.idcoloris', '=', 'variante_produit.idcoloris');
            })
            ->where('contenir.idpanier', $panierId)
            ->select(DB::raw('SUM(contenir.qteproduit * variante_produit.prixproduit) as total'))
            ->value('total');

        DB::table('panier')
            ->where('idpanier', $panierId)
            ->update(['prixpanier' => $totalPrice ?? 0]);

        session()->forget('guest_user_id');
    }
}