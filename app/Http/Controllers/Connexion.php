<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Ajouté
use Carbon\Carbon;
use App\Models\User; // Assure-toi que le modèle est bien importé
use Twilio\Rest\Client; // SDK Twilio

class Connexion extends Controller
{
    public function show()
    {
        return view('connexion');
    }

  public function login(Request $request)
{
    $request->validate([
        'courriel' => ['required', 'email'],
        'mdp'      => ['required'],
    ]);

    $user = User::where('courriel', $request->courriel)->first();

    if ($user && Hash::check($request->mdp, $user->mdp)) {
        
        if ($user->a2f) {
            // RECHERCHE DU TÉLÉPHONE DANS LA TABLE CLIENT
            $telephoneBrut = DB::table('client')
                ->where('idpersonne', $user->idpersonne)
                ->value('telephone');

            if (empty($telephoneBrut)) {
                return back()->withErrors(['courriel' => "Aucun téléphone trouvé pour l'A2F."]);
            }

            // NETTOYAGE ET FORMATAGE (Ta logique de la méthode expedier)
            $telephoneClean = preg_replace('/[^0-9+]/', '', $telephoneBrut);
            if (str_starts_with($telephoneClean, '0')) {
                $telephoneClean = '+33' . substr($telephoneClean, 1);
            }

            $code = rand(100000, 999999);
            //dd($code);

            session([
                'a2f_user_id' => $user->idpersonne,
                'a2f_code'    => $code,
                'a2f_remember' => $request->filled('remember')
            ]);

            try {
                // On envoie le numéro nettoyé
                $this->sendSmsCode($telephoneClean, $code);
            } catch (\Exception $e) {
                return back()->withErrors(['courriel' => "Erreur Twilio : " . $e->getMessage()]);
            }

            return redirect()->route('login.a2f.view');
        }

        // Connexion normale...
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();
        $this->migrateGuestCartToUser(Auth::id());
        return redirect()->intended('/')->with('success', 'Connexion réussie.');
    }

    return back()->withErrors(['courriel' => 'Identifiants incorrects.']);
}

    /**
     * Validation du code A2F
     */
 private function sendSmsCode($phoneNumber, $code)
{
    $sid    = env('TWILIO_USERNAME');
    $token  = env('TWILIO_PASSWORD');
    $from   = env('TWILIO_FROM');

    if (empty($sid) || empty($token)) {
        throw new \Exception("Identifiants Twilio manquants dans le .env");
    }

    $twilio = new \Twilio\Rest\Client($sid, $token);

    $twilio->messages->create(
        $phoneNumber,
        [
            'from' => $from,
            'body' => "Votre code de sécurité FIFA est : $code"
        ]
    );
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
    public function verifyA2f(Request $request)
{

    // 1. On valide que le champ code est rempli
    $request->validate([
        'code' => 'required|numeric'
    ]);

    // 2. On compare avec le code stocké en session lors du login
    if ($request->code == session('a2f_code')) {
        
        $userId = session('a2f_user_id');
        $remember = session('a2f_remember', false);

        // 3. Connexion officielle de l'utilisateur
        Auth::loginUsingId($userId, $remember);
        
        // 4. Régénération de la session pour la sécurité
        $request->session()->regenerate();

        // 5. Migration du panier invité vers son compte
        $this->migrateGuestCartToUser($userId);

        // 6. Nettoyage des variables de session A2F
        session()->forget(['a2f_code', 'a2f_user_id', 'a2f_remember']);

        return redirect()->intended('/')->with('success', 'Connexion réussie avec A2F.');
    }

    // Si le code est faux, on revient en arrière avec une erreur
    return back()->withErrors(['code' => 'Le code de sécurité est incorrect.']);
}
}