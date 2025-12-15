<?php

namespace App\Mail;

// 👇 1. Change l'import ici (Remplace User par Utilisateur)
use App\Models\Utilisateur; 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    // 👇 2. Change le type de la variable publique
    public $utilisateur; 

    // 👇 3. Modifie le constructeur pour accepter "Utilisateur"
    public function __construct(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue sur notre site !',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }
}