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

    /**
     * @OA\Get(
     *     path="/tiposCurso/{id}",
     *     tags={"Tipos de curso"},
     *     security={{"api_key": {}}},
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
     *         @OA\JsonContent(ref="#/components/schemas/TipoCursoDTO"),
     *     ),
     * )
     */
    public function obtenerUno(int $Id){
        $TipoCurso = TipoCurso::find($Id);
        if ($TipoCurso == null){
            return response()->json(null, 404);
        }
        return response()->json($TipoCurso, 200);
    }

    /**
     * @OA\Get(
     *     path="/tiposCurso",
     *     tags={"Tipos de curso"},
     *     security={{"api_key": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TipoCursoDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerLista(){
        $TipoCurso = TipoCurso::all();
        return response()->json($TipoCurso, 200);
    }
    

    /**
     * @OA\Post(
     *     path="/tiposCurso",
     *     tags={"Tipos de curso"},
     *     security={{"api_key": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/TipoCursoDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/TipoCursoDTO"),
     *     ),
     * )
     */
    public function agregar(){
        $TipoCurso = new TipoCurso();
        $TipoCurso->fill($this->request->json()->all());
        if ($TipoCurso->save()) {
            return response()->json($TipoCurso, 200);
        }
        return response()->json(404);
    }
}
