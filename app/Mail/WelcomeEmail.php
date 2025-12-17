<?php

namespace App\Mail;

use App\Models\Utilisateur; 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $utilisateur; 

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