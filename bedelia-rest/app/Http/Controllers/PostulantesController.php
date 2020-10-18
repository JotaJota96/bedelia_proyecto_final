<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Sede;
use App\Models\carrera;
use App\Models\persona;
use App\Models\Direccion;
use App\Models\Postulacion;

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
        $Postulacion = Postulacion::where('id', $id)->first();
        $Postulacion->Persona->direccion;
        $Postulacion->Sede;
        if ($Postulacion == null) {
            return response()->json(['error' => 'Error al buscar la postulacion. '], 404);
        }
        return response()->json($Postulacion, 200);
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
        try {
            DB::beginTransaction();
            $Postulacion = new Postulacion();
            $Postulacion->img_escolaridad = $this->request->json('img_ci');
            $Postulacion->img_ci          = $this->request->json('img_escolaridad');
            $Postulacion->img_carne_salud = $this->request->json('img_carne_salud');
            $Persona = new Persona();
            $Direccion = new Direccion();
            $Persona->fill($this->request->json('persona'));
            $Direccion->fill($this->request->json(['persona', 'direccion']));
            $SedeID = $this->request->json(['sede', 'id']);
            $CarreraID = $this->request->json(['carrera', 'id']);
            $Direccion->save();
            $Persona->direccion()->associate($Direccion);
            $Sede = Sede::where('id', $SedeID)->first();
            $Carrera = Carrera::where('id', $CarreraID)->first();
            if ($Postulacion != null && $Persona != null && $Sede != null && $Carrera != null) {
                $Persona->save();
                $Postulacion->Sede()->associate($Sede);
                $Postulacion->Persona()->associate($Persona);
                $Postulacion->Carrera()->associate($Carrera);
                $Postulacion->save();
                DB::commit();
                return response()->json($Postulacion, 200);
            }
            else {
                DB::rollBack();
                return response()->json(['error' => 'Error al buscar los datos. '], 500);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar el Postulante.' . $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Operacion no implementada aun'], 500);
    }

}
