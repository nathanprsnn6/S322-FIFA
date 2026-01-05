<?php

namespace App\Http\Controllers;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderShippedSMS;


class ExpeditionController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date'); 
        $selectedSlot = $request->input('slot');
        $selectedTransport = $request->input('transport');

        $transportTypes = DB::table('typelivraison')->get();

        $query = DB::table('commande')
            ->join('client', 'commande.idpersonne', '=', 'client.idpersonne')
            ->leftJoin('livrer', 'commande.idcommande', '=', 'livrer.idcommande')
            ->leftJoin('typelivraison', 'livrer.idtypelivraison', '=', 'typelivraison.idtypelivraison')
            ->whereIn('commande.etatcommande', ['En préparation', 'En cours de livraison', 'Livrée'])
            ->select(
                'commande.idcommande',
                'client.nomcomplet',
                'client.ruelivraison',
                'client.cplivraison',
                'client.villelivraison',
                'typelivraison.libelletypelivraison',
                'livrer.datelivraison',
                'livrer.creneaulivraison',
                'commande.etatcommande'
            );

        if ($selectedDate) {
            $query->whereDate('livrer.datelivraison', $selectedDate);
        }

        if ($selectedSlot && $selectedSlot !== 'all') {
            $query->where('livrer.creneaulivraison', $selectedSlot);
        }

        if ($selectedTransport && $selectedTransport !== 'all') {
            $query->where('livrer.idtypelivraison', $selectedTransport);
        }

        $orders = $query
            ->orderByRaw("CASE WHEN commande.etatcommande = 'En préparation' THEN 1 ELSE 2 END")
            ->orderBy('livrer.datelivraison', 'desc')
            ->get();

        return view('expedition', [
            'orders' => $orders,
            'transportTypes' => $transportTypes,
            'currentDate' => $selectedDate,
            'currentSlot' => $selectedSlot,
            'currentTransport' => $selectedTransport
        ]);
    }

    public function expedier($id)
    {
        $nouvelleDate = Carbon::now()->addDays(2);
        
        $exists = DB::table('livrer')->where('idcommande', $id)->exists();

        if ($exists) {
            DB::table('livrer')->where('idcommande', $id)->update(['datelivraison' => $nouvelleDate]);
        } else {
            DB::table('livrer')->insert([
                'idcommande' => $id, 'idtypelivraison' => 2, 'datelivraison' => $nouvelleDate, 'creneaulivraison' => 'Matin'
            ]);
        }

        DB::table('commande')->where('idcommande', $id)->update(['etatcommande' => 'En cours de livraison']);


        $telephoneBrut = DB::table('commande')
            ->join('client', 'commande.idpersonne', '=', 'client.idpersonne')
            ->where('commande.idcommande', $id)
            ->value('client.telephone');

        if ($telephoneBrut) {
            $telephoneClean = preg_replace('/[^0-9+]/', '', $telephoneBrut);
            if (str_starts_with($telephoneClean, '0')) {
                $telephoneClean = '+33' . substr($telephoneClean, 1);
            }
            try {

                $sid    = env('TWILIO_USERNAME');
                $token  = env('TWILIO_PASSWORD');
                $from   = env('TWILIO_FROM');

                if (empty($sid) || empty($token)) {
                    throw new \Exception("Le fichier .env n'est pas lu par Laravel (Cache bloqué).");
                }

                $twilio = new Client($sid, $token);

                $twilio->messages->create(
                    $telephoneClean,
                    [
                        'from' => $from,
                        'body' => "Votre commande #$id est en cours de livraison !"
                    ]
                );
            } catch (\Exception $e) {
                dd($e->getMessage()); 
            }
        }

        return back()->with('success', "Commande #$id expédiée !");
    }
}