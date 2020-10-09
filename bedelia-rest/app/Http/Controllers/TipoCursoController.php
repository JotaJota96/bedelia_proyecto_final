<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Administrativo;
use App\Models\TipoCurso;
use Illuminate\Support\Facades\DB;

class TipoCursoController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function obtenerUno(int $Id){
        $TipoCurso = TipoCurso::find($Id);
        if ($TipoCurso == null){
            return response()->json(null, 404);
        }
        return response()->json($TipoCurso, 200);
    }

    public function obtenerLista(){
        $TipoCurso = TipoCurso::all();
        return response()->json($TipoCurso, 200);
    }
    
    public function agregar(){
        $TipoCurso = new TipoCurso();
        $TipoCurso->fill($this->request->json()->all());
        if ($TipoCurso->save()) {
            return response()->json($TipoCurso, 200);
        }
        return response()->json(404);
    }
}
