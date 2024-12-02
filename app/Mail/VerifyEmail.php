<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $token;

    public function __construct($usuario, $token)
    {
        $this->usuario = $usuario;
        $this->token = $token;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verificación de correo electrónico',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify', // Asegúrate de que esta vista exista
            with: [
                'usuario' => $this->usuario,  // Pasa los datos del usuario
                'token' => $this->token,      // Pasa el token para la verificación
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
