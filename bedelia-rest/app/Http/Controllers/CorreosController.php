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
        //Mail::to('jjap96@gmail.com')->send(new MiCorreo());
        return response()->json(null, 200);
    }
}
