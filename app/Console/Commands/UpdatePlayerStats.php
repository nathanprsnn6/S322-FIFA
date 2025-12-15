<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Statistiques;
use Illuminate\Support\Facades\Http; // Pour faire les appels API

class UpdatePlayerStats extends Command
{
    // Le nom de la commande à lancer dans le terminal
    protected $signature = 'stats:update';
    protected $description = 'Mise à jour nocturne des stats joueurs via API-Football';

    public function handle()
    {
        $this->info("Début de la mise à jour des statistiques...");

        // 1. On récupère toutes les stats qui ont un ID API renseigné pour la saison actuelle
        // (Adapte '2024' selon la saison en cours dans ta logique)
        $saisonActuelle = '2025'; 
        
        $statsAmettreAJour = Statistiques::whereNotNull('api_player_id')
                                        ->where('saison', $saisonActuelle)
                                        ->get();

        $bar = $this->output->createProgressBar(count($statsAmettreAJour));

        foreach ($statsAmettreAJour as $stat) {
            
            // 2. Appel à l'API (Exemple avec API-Football v3 via RapidAPI)
            // Attention : Si tu as beaucoup de joueurs, il vaut mieux appeler par "Equipe" pour économiser les requêtes.
            // Ici je fais appel par joueur pour l'exemple simple.
            
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

                    // Vérification que le joueur a bien des stats renvoyées
                    if (!empty($data['response'][0]['statistics'][0])) {
                        
                        // On cible la compétition principale (souvent l'index 0, mais à adapter selon tes besoins)
                        $apiStats = $data['response'][0]['statistics'][0];

                        // 3. Mise à jour de l'objet Eloquent
                        $stat->update([
                            'matchs_joues'    => $apiStats['games']['appearences'] ?? 0,
                            'titularisations' => $apiStats['games']['lineups'] ?? 0,
                            'minutes_jouees'  => $apiStats['games']['minutes'] ?? 0,
                            'buts'            => $apiStats['goals']['total'] ?? 0,
                            // Pour nb_selections, si c'est les sélections nationales, c'est souvent un autre endpoint ou data
                            // Si c'est juste le total match, on garde appearences.
                        ]);
                    }
                } else {
                    $this->error("Erreur API pour le joueur ID: " . $stat->api_player_id);
                }

            } catch (\Exception $e) {
                $this->error("Exception pour le joueur ID " . $stat->api_player_id . " : " . $e->getMessage());
            }

            // Petite pause pour ne pas spammer l'API si tu n'as pas un plan pro
            sleep(1); 
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Mise à jour terminée !");
    }
}