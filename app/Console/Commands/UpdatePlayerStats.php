<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Statistiques;
use Illuminate\Support\Facades\Http;

class UpdatePlayerStats extends Command
{
    protected $signature = 'stats:update';
    protected $description = 'Mise à jour nocturne des stats joueurs via API-Football';

    public function handle()
    {
        $this->info("Début de la mise à jour des statistiques...");
        $saisonActuelle = '2025'; 
        
        $statsAmettreAJour = Statistiques::whereNotNull('api_player_id')
                                        ->where('saison', $saisonActuelle)
                                        ->get();

        $bar = $this->output->createProgressBar(count($statsAmettreAJour));

        foreach ($statsAmettreAJour as $stat) {
            
            
            try {
                $response = Http::withHeaders([
                    'x-rapidapi-key' => 'TA_CLE_API_ICI',
                    'x-rapidapi-host' => 'api-football-v1.p.rapidapi.com'
                ])->get('https://api-football-v1.p.rapidapi.com/v3/players', [
                    'id' => $stat->api_player_id,
                    'season' => $saisonActuelle
                ]);

                if ($response->successful()) {
                    $data = $response->json();


                    if (!empty($data['response'][0]['statistics'][0])) {
                        
                        $apiStats = $data['response'][0]['statistics'][0];

                        $stat->update([
                            'matchs_joues'    => $apiStats['games']['appearences'] ?? 0,
                            'titularisations' => $apiStats['games']['lineups'] ?? 0,
                            'minutes_jouees'  => $apiStats['games']['minutes'] ?? 0,
                            'buts'            => $apiStats['goals']['total'] ?? 0,
                        ]);
                    }
                } else {
                    $this->error("Erreur API pour le joueur ID: " . $stat->api_player_id);
                }

            } catch (\Exception $e) {
                $this->error("Exception pour le joueur ID " . $stat->api_player_id . " : " . $e->getMessage());
            }

            sleep(1); 
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Mise à jour terminée !");
    }
}