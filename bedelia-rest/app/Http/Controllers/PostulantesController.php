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
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/PostulanteDTO"),
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



    /**
     * @OA\Delete(
     *     path="/postulantes/{id}",
     *     tags={"Postulante"},
     *     description="Elimina la postulacion especifica",
     *     @OA\Parameter(
     *         name="ci",
     *         in="path",
     *         description="ID de la postulacion",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos del usuario",
     *         @OA\JsonContent(ref="#/components/schemas/UsuarioDTO"),
     *     ),
     * )
     */
    public function rechazar($id){
        // Elimina la postulacion especifica, incluyendo datos asociados de Persona y Direccion
        try {
            DB::beginTransaction();
            $Postulacion = Postulacion::where('id', $id)->first();
            $Direccion = $Postulacion->Persona->direccion;
            $Persona = $Postulacion->Persona;
            $Postulacion->delete();
            $Persona->delete();
            $Direccion->delete();
            DB::commit();
            return response()->json(null, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al eliminar la postulacion.' . $e->getMessage()], 500);
        }
    }
    

    /**
     * @OA\Post(
     *     path="/postulantes/{id}/notificar",
     *     tags={"Postulante"},
     *     description="Envia un email al postulante",
     *     @OA\Parameter(
     *         name="ci",
     *         in="path",
     *         description="ID de la postulacion",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
    *     @OA\RequestBody(
    *         @OA\JsonContent(
    *             @OA\Property(property="mensaje", type="string"),
    *         ),
    *     ),
     *     @OA\Response(
     *         response="200",
     *         description=""
     *     ),
     * )
     */
    public function notificar($id){
        // DTO en body
        // Envia un correo al postulante

        return response()->json(["message" => "No implementado aun"], 501);
    }
    

    /**
     * @OA\Post(
     *     path="/postulantes/{id}/aceptar",
     *     tags={"Postulante"},
     *     description="Acepta al postulante generandole una cuenta de estudiante",
     *     @OA\Parameter(
     *         name="ci",
     *         in="path",
     *         description="ID de la postulacion",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description=""
     *     ),
     * )
     */
    public function aceptar($id){
        // dado el ID de una postulacion, se deben crear y asociar el Usuario y Estudiante, asociar el usuario a la Persona (la direccion ya estaba asociada a la persona asi que no hacerle nada), y eliminar el registro de la Postulacion (pero dejando la persona y la direccion)
        
        return response()->json(["message" => "No implementado aun"], 501);
    }
    

}
