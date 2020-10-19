<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EdicionesCursoController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }



    /**
     * @OA\Get(
     *     path="/edicionesCurso/{id}/docente",
     *     tags={"Ediciones Curso"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del edicion curso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/EdicionCursoDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/UsuarioDTO"),
     *     ),
     * )
     */
    public function obtenerDocente($id){
        // viene un UsuarioDTO en body
        // asocia a un EdicionCurso (ya existente) con un Docente (ya existente)

        return response()->json(["message" => "No implementado aun"], 501);
    }
}
