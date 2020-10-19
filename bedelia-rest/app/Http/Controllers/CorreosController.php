<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\CorreoBienvenida;

  //////////////////////////////////////////////////////////////
 // Este controlador es solo para probar el envio de correos //
//////////////////////////////////////////////////////////////

class CorreosController extends Controller {
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function enviarCorreo(){
        $mailData = [
            'destinatario' => $this->request->json('to'),
            'nombre'       => $this->request->json('nombre'),
            'usuario'      => $this->request->json('usuario'),
            'contrasenia'  => $this->request->json('contrasenia'),
        ];

        CorreoBienvenida::enviar($mailData);
        return response()->json(null, 200);
    }
}
