<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\Sede;
use App\Models\AreaEstudio;

class CarrerasController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    
    /**
     * @OA\Get(
     *     path="/carreras/{id}",
     *     tags={"Carreras"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/CarreraDTO"),
     *     ),
     * )
     */
    public function obtenerUno(int $Id){
        $carrera = Carrera::find($Id);
        if ($carrera == null){
            return response()->json(null, 404);
        }
        // obtengo mas info que hay que devolver
        $carrera->sedes;
        foreach ($carrera->sedes as $Id => $value) {
            $value->direccion;
            unset($value->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
        }
        return response()->json($carrera, 200);
    }


    /**
     * @OA\Get(
     *     path="/carreras",
     *     tags={"Carreras"},
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CarreraDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerTodos(){
        $carreras = Carrera::all();

        // obtengo mas info que hay que devolver
        foreach ($carreras as $Id => $c) {
            $c->sedes;
            foreach ($c->sedes as $Id => $s) {
                $s->direccion;
                unset($s->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
            }
        }
        return response()->json($carreras, 200);
    }
}
