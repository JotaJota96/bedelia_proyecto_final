<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostulantesController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * @OA\Get(
     *     path="/postulantes/{id}",
     *     tags={"Postulante"},
     *     description="devuelve una postulacion especifica, incluyendo la persona asociada y su direccion",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la postulacion",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostulanteDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerUno(int $id){
        // devuelve una postulacion especifica, incluyendo la persona asociada y su direccion. Tambien la carrera y la sede

        return response()->json(['message' => 'Operacion no implementada aun'], 500);
    }

    /**
     * @OA\Post(
     *     path="/postulantes",
     *     tags={"Postulante"},
     *     description="Registra en el sistema los datos de un postulante",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/PostulanteDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/PostulanteDTO"),
     *     ),
     * )
     */
    public function agregar(){
        // registra en el sistema los datos de un postulante (incluyendo su Persona y Direccion asociados)
        // la postulacion se debe asociar a la sede y a una carrera

        return response()->json(['message' => 'Operacion no implementada aun'], 500);
    }

}
