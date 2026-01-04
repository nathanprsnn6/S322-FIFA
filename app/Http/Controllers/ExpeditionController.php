<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpeditionController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::now()->toDateString());
        $selectedSlot = $request->input('slot');
        $selectedTransport = $request->input('transport');

        $transportTypes = DB::table('typelivraison')
            ->select('idtypelivraison', 'libelletypelivraison')
            ->get();

        $query = DB::table('livrer')
            ->join('commande', 'livrer.idcommande', '=', 'commande.idcommande')
            ->join('client', 'commande.idpersonne', '=', 'client.idpersonne')
            ->join('typelivraison', 'livrer.idtypelivraison', '=', 'typelivraison.idtypelivraison')
            ->where('commande.etatcommande', '!=', 'Annulée')
            ->whereDate('livrer.datelivraison', $selectedDate)
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

        if ($selectedSlot && $selectedSlot !== 'all') {
            $query->where('livrer.creneaulivraison', $selectedSlot);
        }

        if ($selectedTransport && $selectedTransport !== 'all') {
            $query->where('livrer.idtypelivraison', $selectedTransport);
        }

        $orders = $query->orderBy('livrer.creneaulivraison', 'desc')->get();

        return view('produitService', [
            'orders' => $orders,
            'transportTypes' => $transportTypes,
            'currentDate' => $selectedDate,
            'currentSlot' => $selectedSlot,
            'currentTransport' => $selectedTransport
        ]);
    }
}