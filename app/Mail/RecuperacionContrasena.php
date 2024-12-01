<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Token;
use Illuminate\Support\Facades\Log;
use App\Models\Usuario;
use Illuminate\Support\Str; //
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class RecuperacionContrasena extends Mailable
{

    use Queueable, SerializesModels;

    public $usuario;
    public $token;
    public $resetUrl;

    public function __construct($usuario, $token)
    {
        $this->usuario = $usuario;
        $this->token = $token;
        $this->resetUrl = url('/reset-password/' . $token);
    }

    public function build()
    {
        return $this->view('emails.recuperacion-contrasena')
                    ->subject('Recuperación de Contraseña');
    }
}
