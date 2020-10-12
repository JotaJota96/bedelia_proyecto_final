<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\Sede;
use App\Models\AreaEstudio;
use App\Models\Curso;
use App\Models\Previa;

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

    /**
     * @OA\Get(
     *     path="/carreras/{id}/cursos",
     *     tags={"Carreras"},
     *     description="Devuelve la lista de cursos que conforman la carrera",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CursoDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerCursosDeCarrera(int $Id){
        $carrera = Carrera::find($Id);
        if ($carrera == null){
            return response()->json(null, 404);
        }
        // obtengo la lista de cursos
        $cursos = $carrera->cursos;

        foreach ($cursos as $Id => $value) {
            $value->optativo = $value->pivot->optativo;
            $value->semestre = $value->pivot->semestre;
            unset($value->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
        }
        return response()->json($cursos, 200);
    }

    /**
     * @OA\Get(
     *     path="/carreras/{id}/previas",
     *     tags={"Carreras"},
     *     description="Devuelve las relaciones de previas entre los cursos que conforman la carrera",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PreviaDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerPreviasEntreCursosDeCarrera(int $Id){
        $carrera = Carrera::find($Id);
        if ($carrera == null){
            return response()->json(null, 404);
        }
        // obtengo la lista de cursos
        $previas = $carrera->previas;

        return response()->json($previas, 200);
    }
    
}
