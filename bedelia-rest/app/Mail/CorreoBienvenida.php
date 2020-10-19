<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CorreoBienvenida extends Mailable
{
    use Queueable, SerializesModels;
    private $mailData     = null;
    private static $expectedData = ['destinatario', 'nombre', 'usuario', 'contrasenia'];

    public function __construct($mailData = null) {
        $this->mailData = $mailData;
    }

    public static function enviar($mailData = null){
        CorreoBienvenida::validarDatos($mailData);

        $mailData['webUrl'] = env('WEB_URL');

        // envia el correo solo si se habilita desde la configuracion
        if (env('MAIL_SEND')){
            try {
                Mail::to($mailData['destinatario'])->send(new CorreoBienvenida($mailData));
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
            CorreoBienvenida::validarDatos($this->mailData);
            
            return $this->subject('Usuario creado')
                ->view('correo-bienvenida')
                ->with($this->mailData);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private static function validarDatos($datos){
        if ($datos == null){
            throw new \Exception("No se especificaron datos para rellenar el correo");
        }
        $expectedData = CorreoBienvenida::$expectedData;
        foreach ($expectedData as $value) {
            if ( ! array_key_exists($value, $datos)){
                throw new \Exception("Para enviar el correo se debe especificar: {" . implode(" ",$expectedData) . "}. Faltó '$value'");
            }
        }
    }

}
