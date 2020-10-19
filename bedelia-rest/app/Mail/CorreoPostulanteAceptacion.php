<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CorreoPostulanteAceptacion extends Mailable
{
    use Queueable, SerializesModels;
    private $mailData     = null;
    private static $expectedData = ['destinatario', 'nombre', 'nombreCarrera', 'usuario', 'contrasenia'];

    public function __construct($mailData = null) {
        $this->mailData = $mailData;
    }

    public static function enviar($mailData = null){
        CorreoPostulanteAceptacion::validarDatos($mailData);

        $mailData['webUrl'] = env('WEB_URL');

        // envia el correo solo si se habilita desde la configuracion
        if (env('MAIL_SEND')){
            try {
                Mail::to($mailData['destinatario'])->send(new CorreoPostulanteAceptacion($mailData));
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    public function build() {
        if ($this->mailData == null){
            throw new \Exception("No se especificaron datos para rellenar el correo");
        }
        try {
            CorreoPostulanteAceptacion::validarDatos($this->mailData);
            
            return $this->subject('Inscripción confirmada')
                ->view('correo-postulante-aceptacion')
                ->with($this->mailData);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private static function validarDatos($datos){
        if ($datos == null){
            throw new \Exception("No se especificaron datos para rellenar el correo");
        }
        $expectedData = CorreoPostulanteAceptacion::$expectedData;
        foreach ($expectedData as $value) {
            if ( ! array_key_exists($value, $datos)){
                throw new \Exception("Para enviar el correo se debe especificar: {" . implode(" ",$expectedData) . "}. Faltó '$value'");
            }
        }
    }

}
