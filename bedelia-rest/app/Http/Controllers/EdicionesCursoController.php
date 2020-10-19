<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EdicionCurso;
use App\Models\Docente;
use App\Models\Usuario;
use App\Models\Persona;

class EdicionesCursoController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }



    /**
     * @OA\Put(
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
    public function asignarDocente($id){
        // viene un UsuarioDTO en body
        // asocia a un EdicionCurso (ya existente) con un Docente (ya existente)
        try {
            DB::beginTransaction();
            $EdicionCurso = EdicionCurso::where('id', $id)->first();
            $UsuarioDTO = new persona();
            $UsuarioDTO->fill($this->request->json(['persona']));
            // return response()->json($EdicionCurso, 200);
            $Docente = Usuario::buscar($UsuarioDTO->cedula)->docente;
            $Docente->usuario;
            // return response()->json($Docente, 200);
            $EdicionCurso->docente()->associate($Docente);
            $EdicionCurso->save();
            DB::commit();
            return response()->json(null, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
    }
}
