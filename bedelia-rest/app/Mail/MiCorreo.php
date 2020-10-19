<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MiCorreo extends Mailable
{
    use Queueable, SerializesModels;
    private $mailData = null;

    public function __construct($mailData = null) {
        $this->mailData = $mailData;
    }

    public function build() {
        if ($this->mailData == null){
            throw new Exception("No se especificaron datos para rellenar el correo");
        }
        return $this->subject('asunto')
            ->view('mi-correo')
            ->with($this->mailData);
    }
}
