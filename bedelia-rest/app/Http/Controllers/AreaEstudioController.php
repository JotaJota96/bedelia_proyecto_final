<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Administrativo;
use App\Models\AreaEstudio;
use Illuminate\Support\Facades\DB;

class AreaEstudioController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function obtenerUno(int $Id){
        $AreaEstudio = AreaEstudio::find($Id);
        if ($AreaEstudio == null){
            return response()->json(null, 404);
        }
        return response()->json($AreaEstudio, 200);
    }

    public function obtenerLista(){
        $AreasEstudio = AreaEstudio::all();
        return response()->json($AreasEstudio, 200);
    }
    
    public function agregar(){
        $AreaEstudio = new AreaEstudio();
        $AreaEstudio->fill($this->request->json()->all());
        if ($AreaEstudio->save()) {
            return response()->json($AreaEstudio, 200);
        }
        return response()->json(404);
    }
}
