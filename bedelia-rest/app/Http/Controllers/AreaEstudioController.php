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

    /**
     * @OA\Get(
     *     path="/areasEstudio/{id}",
     *     tags={"Areas de estudio"},
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
     *         @OA\JsonContent(ref="#/components/schemas/AreaEstudioDTO"),
     *     ),
     * )
     */
    public function obtenerUno(int $Id){
        $AreaEstudio = AreaEstudio::find($Id);
        if ($AreaEstudio == null){
            return response()->json(null, 404);
        }
        return response()->json($AreaEstudio, 200);
    }

    /**
     * @OA\Get(
     *     path="/areasEstudio",
     *     tags={"Areas de estudio"},
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AreaEstudioDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerLista(){
        $AreasEstudio = AreaEstudio::all();
        return response()->json($AreasEstudio, 200);
    }

    /**
     * @OA\Post(
     *     path="/areasEstudio",
     *     tags={"Areas de estudio"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/AreaEstudioDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/AreaEstudioDTO"),
     *     ),
     * )
     */
    public function agregar(){
        $AreaEstudio = new AreaEstudio();
        $AreaEstudio->fill($this->request->json()->all());
        if ($AreaEstudio->save()) {
            return response()->json($AreaEstudio, 200);
        }
        return response()->json(404);
    }

    /**
     * @OA\Get(
     *     path="/areasEstudio/{id}/cursos",
     *     tags={"Areas de estudio"},
     *     description="Devuelve los cursos que pertenecen al area de estudio",
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
     *             @OA\Items(ref="#/components/schemas/AreaEstudioDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerCursosPertenecientesAUnArea(int $Id){
        $AreaEstudio = AreaEstudio::find($Id);
        if ($AreaEstudio == null){
            return response()->json(null, 404);
        }
        foreach ($AreaEstudio->cursos as $Id => $value) {
            $value->AreaEstudio;
            $value->TipoCurso;
        }
        return response()->json($AreaEstudio->cursos, 200);
    }
}
