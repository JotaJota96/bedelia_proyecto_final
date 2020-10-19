<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MiCorreo;

class CorreosController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function enviarCorreo(){
        // envia el correo solo si se habilita desde la configuracion
        if (env('MAIL_SEND')){
            try {
                $datos = [
                    "nombre" => "José",
                    "url" => env('APP_URL'),
                ];

                Mail::to('jjap96@gmail.com')->send(new MiCorreo($datos));
            } catch (\Exception $e) {
                return response()->json(
                    [
                        "message" => "No se pudo enviar el gorreo",
                        "details" => $e->getMessage()
                    ],
                    500
                );
            }
        }else{
            return response()->json("Correos desactivados", 200);
        }
        return response()->json("Correo enviado", 200);
    }
}
