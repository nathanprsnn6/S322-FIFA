<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FifaExpertConversation extends Conversation
{
    protected $staticKnowledge = "
        CONTEXTE DU SITE FIFA STORE & VOTE :
        
        1. VOTES :
        - Qui peut voter : Tout le monde peut voir, mais il faut être connecté pour valider.
        - Règles : Il faut choisir exactement 3 joueurs par catégorie.
        
        2. BOUTIQUE & PRODUITS :
        - Recherche : Possible par Nation, Catégorie, Compétition.
        - Prix : Dépend du coloris, pas de la taille.
        - Paiement : Sécurisé SSL/TLS. Cartes bancaires uniquement (Visa, Mastercard, Amex).
        
        3. LIVRAISON :
        - Standard (Geodis, Trusk, etc.) : 9,00 €, délai 2 à 5 jours.
        - Express (France Express) : 16,50 €, délai garanti 3 jours.
        - Suivi : Numéro envoyé par email ou via 'Mon Compte' > 'Suivre mon colis'.
        - Réserves : Si colis abîmé, 15 jours pour confirmer la réclamation.
        
        4. RETOURS :
        - Délai retour : 14 jours après réception.
        - Annulation : Possible tant que non expédié via 'Mes commandes'.
    ";

    public function run()
    {
        $incomingMessage = $this->bot->getMessage()->getText();
        
        $isGreeting = preg_match('/^(bonjour|hello|start|commencer|aide|test)$/i', $incomingMessage);

        if ($isGreeting) {
            $this->say('Bonjour ! Je suis l\'assistant FIFA. Je peux vous aider avec les votes, la boutique ou vos commandes.');
        } else {
            $userContext = $this->getUserContext();
            $response = $this->getGeminiResponse($incomingMessage, $userContext);
            $this->say($response);
        }

        $this->askAi();
    }

    public function askAi()
    {
        $this->ask('Je vous écoute...', function(Answer $answer) {
            $question = $answer->getText();
            
            $userContext = $this->getUserContext();
            $response = $this->getGeminiResponse($question, $userContext);

            $this->say($response);
            
            $this->askAi();
        });
    }

    protected function getUserContext()
    {
        try {
            if (!Auth::check()) {
                return "UTILISATEUR : Visiteur non connecté.";
            }

            $userId = Auth::id();
            
            $user = DB::table('utilisateur')
                ->join('personne', 'utilisateur.idpersonne', '=', 'personne.idpersonne')
                ->where('utilisateur.idpersonne', $userId)
                ->first();

            $orders = DB::table('commande')
                ->where('idpersonne', $userId)
                ->orderBy('idcommande', 'desc')
                ->limit(3)
                ->get();

            $orderText = "";
            if ($orders->isEmpty()) {
                $orderText = "Aucune commande récente.";
            } else {
                foreach ($orders as $cmd) {
                    $orderText .= "- Commande #{$cmd->idcommande} : {$cmd->etatcommande}\n";
                }
            }

            $prenom = $user->prenom ?? 'Client';
            return "UTILISATEUR : {$prenom} (Email: {$user->courriel})\n" .
                   "HISTORIQUE COMMANDES :\n" . $orderText;

        } catch (\Exception $e) {
            return "UTILISATEUR : Erreur lecture profil.";
        }
    }

    protected function getGeminiResponse($message, $userContext)
    {
        $apiKey = getenv('GOOGLE_API_KEY');
        $model = 'gemini-3-flash-preview'; 

        $client = new Client([
            'verify' => false, 
            'timeout' => 10.0,
        ]);

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $apiKey;

        $systemPrompt = "Rôle: Assistant FIFA Store.
        Contexte: {$this->staticKnowledge}
        Info Client: {$userContext}
        Question: {$message}
        Consigne: Réponds de manière courte, naturelle et utile.
        IMPORTANT : N'utilise JAMAIS de mise en forme Markdown (pas de gras, pas d'italique, pas de titres). Écris en texte brut uniquement.";

        try {
            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $systemPrompt]
                            ]
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Je n\'ai pas trouvé de réponse.';

            $botReply = str_replace(['**', '##', '###', '*', '__'], '', $botReply);
            
            return $botReply;

        } catch (\Exception $e) {
            return "Désolé, une erreur technique est survenue.";
        }
    }
}