<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Transaction;
use App\Models\CarteBancaire;
use App\Models\Clients;
use App\Models\Panier; 
use App\Models\Contenir;


class Payer extends Controller
{
    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'nom_complet' => 'required|string|max:100',
            'adr' => 'required|string|max:100',
            'cp' => 'required|string|max:10',
            'pays' => 'required|string|max:30',
            'ville' => 'required|string|max:30',
            'tel' => 'required|string|min:10|max:20',

            'card_number_saisie' => 'required|string|between:13,19', 
            'card_name_saisie' => 'required|string|max:200',
            'expiry_date_saisie' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],

            'delivery_method' => 'required|in:1,2',
            'billing_address' => 'required|in:same,different',
        ]);

        $expiry = $validated['expiry_date_saisie'];
        list($month, $year) = explode('/', $expiry);
        $year = '20' . $year;
        $expiryDate = \DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-01');
        $expiryDate->modify('last day of this month')->setTime(23, 59, 59);
        $now = new \DateTime();
        if ($expiryDate < $now) {
            return back()->withErrors(['expiry_date_saisie' => 'La date d\'expiration de la carte est expirée.'])->withInput();
        }

        $telephone = preg_replace('/(?!^\+)\D/', '', $validated['tel']);

        $userId = Auth::id();
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour finaliser la commande.');
        }

        DB::beginTransaction();
        try {
            
            DB::table('client')->updateOrInsert(
                ['idpersonne' => $userId],
                [
                'cplivraison' => $validated['cp'],
                'villelivraison' => $validated['ville'],
                'telephone' => $telephone,
                'payslivraison' => $validated['pays'],
                'nomcomplet' => $validated['nom_complet'],
                'ruelivraison' => $validated['adr'],
                ]
            );

            $carteData = [
                'refcb' => $validated['card_number_saisie'],
                'dateexpirationcb' => $validated['expiry_date_saisie'],
                'nomcb' => $validated['card_name_saisie'],
            ];

            $existingCarte = CarteBancaire::where('idpersonne', $userId)->first();

            if ($existingCarte) {
                $existingCarte->refcb = $carteData['refcb'];
                $existingCarte->dateexpirationcb = $carteData['dateexpirationcb'];
                $existingCarte->nomcb = $carteData['nomcb'];
                $existingCarte->save();

                $idcb = $existingCarte->idcb;
            } else {
                $newCarte = new CarteBancaire();
                $newCarte->idpersonne = $userId;
                $newCarte->refcb = $carteData['refcb'];
                $newCarte->dateexpirationcb = $carteData['dateexpirationcb'];
                $newCarte->nomcb = $carteData['nomcb'];
                $newCarte->save();

                $idcb = $newCarte->idcb;
            }

            $prixpanier = DB::table('panier')
                ->where('idpersonne', $userId)
                ->value('prixpanier');

            $panierActif = Panier::where('idpersonne', $userId)->first();

            $idtransaction = DB::table('transaction')->insertGetId([
                'idcb' => $idcb,
                'datetransaction' => now(),
                'montanttransaction' => $prixpanier ?? 0,
            ],'idtransaction');

            DB::table('commande')->insert([
                'idpanier' => $panierActif->idpanier,
                'idtransaction' => $idtransaction,
                'idpersonne' => $userId,
                'etatcommande' => 'En cours de livraison',
            ]);

            DB::table('serviceexpedition')->insert([
                'idtypelivraison' => $validated['delivery_method'],
                'libelleserviceexpedition' => $validated['delivery_method'] == 1 ? 'Gestion Fifa Normal' : 'Gestion Fifa Express',
            ]);

            

            //Contenir::where('idpanier', $panierActif->idpanier)->delete();
            //Panier::where('idpanier', $panierActif->idpanier)->delete();

            // Si toutes les opérations sont réussies, on valide la transaction
            DB::commit();
            
            Log::info('Paiement réussi pour utilisateur ' . $userId);
            return redirect('/')->with('success', 'Vos informations de paiement et de livraison ont été enregistrées avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur lors du traitement du paiement pour l'utilisateur {$userId}: " . $e->getMessage());
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement de vos informations. Veuillez réessayer.'])->withInput();
        }
    }
}