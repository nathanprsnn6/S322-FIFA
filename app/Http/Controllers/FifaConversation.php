<?php

namespace App\Http\Controllers;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class FifaConversation extends Conversation
{
    public function run()
    {
        $this->showMainMenu();
    }

    public function showMainMenu()
    {
        $question = Question::create('Bonjour ! Choisissez un thème ou posez-moi directement votre question :')
            ->fallback('Je n\'ai pas compris.')
            ->callbackId('main_menu')
            ->addButtons([
                Button::create('Le Vote (The Best)')->value('vote'),
                Button::create('Inscription / Compte')->value('inscription'),
                Button::create('Boutique / Produits')->value('boutique'),
                Button::create('Livraison')->value('livraison'),
                Button::create('Paiement')->value('paiement'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->handleButtonClick($answer->getValue());
            } else {
                $this->handleUserText($answer->getText());
            }
        });
    }

    public function handleButtonClick($value)
    {
        switch ($value) {
            case 'vote':
                $this->say("Pour voter, allez dans la section 'Voter', choisissez une catégorie (Joueur, Joueuse, Entraîneur) et cliquez sur le bouton.");
                break;
            case 'inscription':
                $this->say("Cliquez sur 'Inscription'. La création se fait en 3 étapes (Données personnelles, Surnom, Mot de passe).");
                break;
            case 'boutique':
                $this->say("Dans la boutique, utilisez la recherche par Nation, Catégorie ou Compétition. Le prix dépend du coloris, pas de la taille.");
                break;
            case 'livraison':
                $this->say("En Express (France Express), c'est 3 jours garantis. En standard, comptez 2 à 5 jours selon le transporteur (Geodis, Trusk, etc.).");
                break;
            case 'paiement':
                $this->say("Le règlement des achats sur le site se fait uniquement par carte bancaire.");
                break;
            default:
                $this->say("Je n'ai pas cette information.");
        }
    }

    public function handleUserText($message)
    {
        $msg = strtolower($message);

        if (preg_match('/connex|connect|valid.*vote|clic.*vote/i', $msg)) {
            $this->say("Attention : Tout le monde peut consulter le vote, mais vous devez être connecté pour le valider.");
        } elseif (preg_match('/vote|voter|meilleur.*joueur|best/i', $msg)) {
            $this->say("Pour voter, rendez-vous dans la section 'Voter'. Choisissez votre catégorie (Joueur, Joueuse, Entraîneur).");
        } elseif (preg_match('/inscri|compte|créer/i', $msg)) {
            $this->say("Pour créer un compte, cliquez sur 'Inscription'. Cela se fait en 3 étapes. Vous recevrez un mail de validation.");
        } elseif (preg_match('/délai|temps|quand|arriver/i', $msg)) {
            $this->say("Les délais dépendent du mode : 3 jours pour l'Express, 2 à 5 jours pour le standard.");
        } elseif (preg_match('/transporteur|livreur|express|chronopost/i', $msg)) {
            $this->say("Nous travaillons avec France Express, Geodis, Trusk, Heppner et Chronopost selon votre localisation.");
        } elseif (preg_match('/cherch|trouv|filtre|nation|catégorie/i', $msg)) {
            $this->say("Dans la boutique, utilisez les filtres par Nation, Catégorie ou Compétition.");
        } elseif (preg_match('/prix.*taille|taille.*prix/i', $msg)) {
            $this->say("Le prix dépend uniquement du coloris choisi mais reste le même quelle que soit la taille.");
        } elseif (preg_match('/paiement|payer|paypal|chèque|carte/i', $msg)) {
            $this->say("Le règlement se fait uniquement par carte bancaire.");
        } elseif (preg_match('/bonjour|salut|hello/i', $msg)) {
            $this->say("Bonjour ! Je peux vous aider sur les votes, la boutique ou la livraison. Cliquez sur un bouton ou posez votre question.");
            $this->showMainMenu(); 
        } else {
            $this->say("Je ne suis pas sûr de comprendre. Essayez des mots clés comme 'livraison', 'vote' ou 'paiement'.");
        }
    }
}