<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $utilisateur;

    public function __construct($token, $utilisateur)
    {
        $this->token = $token;
        $this->utilisateur = $utilisateur;
    }

    public function build()
    {
        return $this->subject('Réinitialisation de votre mot de passe FIFA')
                    ->view('emails.reset-password'); // Le nom de ta vue HTML
    }
}