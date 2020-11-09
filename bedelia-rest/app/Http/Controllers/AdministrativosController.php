<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Administrativo;
use App\Models\Sede;

class AdministrativosController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * @OA\Get(
     *     path="/administrativos/{ci}/sede/",
     *     tags={"Administrativos"},
     *     description="Devuelve la sede en la que trabaja el usuario administrativo",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="ci",
     *         in="path",
     *         description="Cédula del usuario administrativo",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve la sede donde trabaja el usuario administratvo",
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     * )
     */
    public function obtenerSede($ci){
        $u = Usuario::buscar($ci);
        if ($u == null || $u->administrativo == null){
            return response()->json(["message" => "Administrativo no encontrado"], 404);
        }

        $a = $u->administrativo;
        $sede = $a->sede;

        if ($sede != null){
            $sede->direccion;
        }

        return response()->json($sede, 200);
    }

    /**
     * @OA\Post(
     *     path="/administrativos/{ci}/sede/",
     *     tags={"Administrativos"},
     *     description="Establece la sede en la que trabaja el usuario administrativo",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="ci",
     *         in="path",
     *         description="Cédula del usuario administrativo",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve la sede donde trabaja el usuario administratvo",
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     * )
     */
    public function establecerSede($ci){
        $u = Usuario::buscar($ci);
        if ($u == null || $u->administrativo == null){
            return response()->json(["message" => "Administrativo no encontrado"], 404);
        }
        $a = $u->administrativo;

        $sede = new Sede();
        $sede = Sede::find($this->request->json('id'));
        if ($sede == null){
            return response()->json(["message" => "Sede no encontrada"], 404);
        }

        $a->sede()->associate($sede)->save();

        $sede = $a->sede;
        $sede->direccion;

        return response()->json($sede, 200);
    }


}
