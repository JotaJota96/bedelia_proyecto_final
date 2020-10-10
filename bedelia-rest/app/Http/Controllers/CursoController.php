<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Administrativo;
use App\Models\Curso;
use App\Models\TipoCurso;
use App\Models\AreaEstudio;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function obtenerUno(int $Id){
        $Curso = Curso::find($Id);
        if ($Curso == null){
            return response()->json(null, 404);
        }
        $Curso->AreaEstudio;
        $Curso->TipoCurso;
        return response()->json($Curso, 200);
    }

    public function obtenerLista(){
        $Curso = Curso::all();
        return response()->json($Curso, 200);
    }
    
    public function agregar(){
        $Curso = new Curso();
        $AreaEstudio = AreaEstudio::find($this->request->json(['area_estudio','id']));
        $TipoCurso = TipoCurso::find($this->request->json(['tipo_curso','id']));
        // $TipoCurso->fill($this->request->json('tipo_curso'));
        // $AreaEstudio->fill($this->request->json('area_estudio'));
        $Curso->fill($this->request->json()->all());
        $Curso->TipoCurso()->associate($TipoCurso);
        $Curso->AreaEstudio()->associate($AreaEstudio);
        // return response()->json($Curso, 200);
        if ($Curso->save()) {
            return response()->json($Curso, 200);
        }
        return response()->json(404);
    }
}
