<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Sede;
use App\Models\Carrera;
use App\Models\Persona;
use App\Models\Direccion;
use App\Models\Postulacion;
use App\Models\Usuario;
use App\Models\Estudiante;
use App\Models\InscripcionCarrera;
use App\Mail\CorreoPostulanteNotificacion;
use App\Mail\CorreoPostulanteAceptacion;

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
     *     security={{"api_key": {}}},
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
            return response()->json(['message' => 'Error al buscar la postulacion. '], 404);
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

            $reciclarPersona = false;
            $perVerific1 = Persona::where('cedula', $Persona->cedula)->first();
            $perVerific2 = Persona::where('correo', $Persona->correo)->first();
            
            if ($perVerific1 == null && $perVerific2 == null){
                // si ni la cedula ni el correo se estaban usando
                // no hago nada, que siga todo normal
            }else if ($perVerific1 != null && $perVerific2 != null){
                // si se encontraron usuarios tanto por cedula como por correo
                // verifico si son el mismo
                if ($perVerific1->id == $perVerific2->id){
                    // si son el mismo usuario, puedo reciclarlo
                    $reciclarPersona = true;
                }else{
                    // sino no se puede postular
                    throw new \Exception("Ya existe un usuario con el correo o la cedula del postulante");
                }
            }else{
                // sino no se puede postular
                throw new \Exception("Ya existe un usuario con el correo o la cedula del postulante");
            }

            $SedeID = $this->request->json(['sede', 'id']);
            $CarreraID = $this->request->json(['carrera', 'id']);

            if ($reciclarPersona){
                $Persona = Persona::where('cedula', $Persona->cedula)->first();
            }else{
                $Direccion->save();
                $Persona->direccion()->associate($Direccion);
                $Persona->save();
            }

            $Sede = Sede::where('id', $SedeID)->first();
            $Carrera = Carrera::where('id', $CarreraID)->first();
            
            if ($Postulacion != null && $Persona != null && $Sede != null && $Carrera != null) {
                $Postulacion->Sede()->associate($Sede);
                $Postulacion->Persona()->associate($Persona);
                $Postulacion->Carrera()->associate($Carrera);
                $Postulacion->save();
                DB::commit();
                return response()->json($Postulacion, 200);
            }
            else {
                DB::rollBack();
                return response()->json(['message' => 'Error al buscar los datos. '], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al guardar el Postulante.' . $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Operacion no implementada aun'], 500);
    }



    /**
     * @OA\Delete(
     *     path="/postulantes/{id}",
     *     tags={"Postulante"},
     *     description="Elimina la postulacion especifica",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
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
    public function rechazar($id){
        // Elimina la postulacion especifica, incluyendo datos asociados de Persona y Direccion
        try {
            DB::beginTransaction();
            $Postulacion = Postulacion::where('id', $id)->first();
            $Postulacion->estado = 'R';
            $Postulacion->save();
            DB::commit();
            return response()->json(null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al eliminar la postulacion.' . $e->getMessage()], 500);
        }
    }
    

    /**
     * @OA\Post(
     *     path="/postulantes/{id}/notificar",
     *     tags={"Postulante"},
     *     description="Envia un email al postulante",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
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
        try {
            $postu = Postulacion::find($id);
            if ($postu == null){
                return response()->json(['message' => 'Postulante no encontrado.'], 404);
            }
            $destinatario = $postu->persona->correo;
            $mensaje = $this->request->json('mensaje');

            if ($mensaje == null || strlen($mensaje) == 0 ){
                return response()->json(['message' => 'Debe especificar un mensaje.'], 500);
            }
            // enviar correo
            $mailData = [
                'destinatario' => $postu->persona->correo,
                'nombre'       => $postu->persona->nombre,
                'mensaje'      => $this->request->json('mensaje'),
            ];
            CorreoPostulanteNotificacion::enviar($mailData);
            $postu->estado = 'N';
            $postu->save();
            return response()->json(['message' => "Correo enviado a $destinatario"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al enviar el correo.' . $e->getMessage()], 500);
        }
    }
    

    /**
     * @OA\Post(
     *     path="/postulantes/{id}/aceptar",
     *     tags={"Postulante"},
     *     description="Acepta al postulante generandole una cuenta de estudiante",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
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
    public function aceptar($id){
        // dado el ID de una postulacion, se deben crear y asociar el Usuario y Estudiante, asociar el usuario a la Persona (la direccion ya estaba asociada a la persona asi que no hacerle nada), y eliminar el registro de la Postulacion (pero dejando la persona y la direccion)
        // Pseudocodig:
        // Abrir transaccion
        //   obtengo la postulacion
        //   creo el Usuario y lo asocio a la Persona de la postulacion
        //   establecer la contrasenia del Usuario
        //   guardar el Usuario y el Estudiante
        //   guardar la relacion entre usuario e inscripcion a carrera
        //   eliminar la postulacion
        // commit
        // enviar correo al estudiante

        try {
            DB::beginTransaction();

            $reciclarUsuario = false;

            // obtengo la postulacion
            $postu = Postulacion::find($id);
            if ($postu == null){
                return response()->json(['message' => 'Postulante no encontrado.'], 404);
            }
            if (strcmp($postu->estado, "A") == 0){
                throw new \Exception("No se puede aceptar la postulacion, ya fue aceptada anteriormente");
            }

            $usuVerific1 = Usuario::buscar($postu->persona->cedula);
            $usuVerific2 = Usuario::buscar($postu->persona->correo);
            
            if ($usuVerific1 == null && $usuVerific2 == null){
                // si ni la cedula ni el correo se estaban usando
                // no hago nada, que siga todo normal
            }else if ($usuVerific1 != null && $usuVerific2 != null){
                // si se encontraron usuarios tanto por cedula como por correo
                // verifico si son el mismo
                if ($usuVerific1->id == $usuVerific2->id){
                    // si son el mismo usuario, puedo reciclarlo
                    $reciclarUsuario = true;
                }else{
                    // sino no se puede postular
                    throw new \Exception("Ya existe un usuario con el correo o la cedula del postulante");
                }
            }else{
                // sino no se puede postular
                throw new \Exception("Ya existe un usuario con el correo o la cedula del postulante");
            }

            // si el usuario ya estaba registrado, solo le asigno el rol (si no lo tenia) y registro la inscripcion
            if ($reciclarUsuario){
                $usu = Usuario::buscar($postu->persona->cedula);
                // si el usuario no tenia el rol de estudiante
                $est = $usu->estudiante;
                if ($est == null){
                    // genero asocio y el Estudiante al Usuario
                    $est = new Estudiante();
                    $est->usuario()->associate($usu);
                    $est->save();
                }

                // guardar la relacion entre usuario e inscripcion a carrera
                $insc = new InscripcionCarrera();
                $insc->estudiante()->associate(Estudiante::find($usu->id));
                $insc->carrera()->associate(Carrera::find($postu->carrera->id));
                $insc->sede()->associate(Sede::find($postu->sede->id));
                $insc->save();
            }else{
                // creo el Usuario y lo asocio a la Persona de la postulacion
                $usu = new Usuario();
                $usu->persona()->associate($postu->persona);
                // establecer la contrasenia del Usuario
                $usu->contrasenia = $usu->persona->cedula;
                // para contraseña encriptada
                //$usu->contrasenia = Crypt::decrypt($usu->contrasenia);
                
                // guardar el Usuario y el Estudiante
                $usu->save();
                $est = new Estudiante();
                $est->usuario()->associate($usu);
                $est->save();

                // guardar la relacion entre usuario e inscripcion a carrera
                $insc = new InscripcionCarrera();
                $insc->estudiante()->associate(Estudiante::find($usu->id));
                $insc->carrera()->associate(Carrera::find($postu->carrera->id));
                $insc->sede()->associate(Sede::find($postu->sede->id));
                $insc->save();
            }

            // marca la postulacion como aceptada
            $postu->estado = 'A';
            $postu->save();

            DB::commit();

            // enviar correo al estudiante
            try {
                // enviar correo
                $mailData = [
                    'destinatario'  => $usu->persona->correo,
                    'nombre'        => $usu->persona->nombre,
                    'nombreCarrera' => $insc->carrera->nombre,
                    'usuario'       => $usu->persona->cedula,
                    'contrasenia'   => $usu->persona->cedula,
                ];
                if ($reciclarUsuario){
                    $mailData = [
                    'usuario'       => "(su usuario actual)",
                    'contrasenia'   => "(su contrasenia actual)",
                    ];
                }
                CorreoPostulanteAceptacion::enviar($mailData);
            } catch (\Exception $e) {
            }

            $est->usuario->persona->direccion;
            return response()->json($est->usuario, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // devuelve un estado HTTP 500 y un mensaje simple del error
            return response()->json(["message" => "Error al guardar los datos." . $e->getMessage()], 500);
        }
    }
    

}

/*

{
  "img_ci": "string",
  "img_escolaridad": "string",
  "img_carne_salud": "string",
  "sede": {
    "id": 1
  },
  "carrera": {
    "id": 1
  },
  "persona": {
    "cedula": "47101475",
    "nombre": "string",
    "apellido": "string",
    "correo": "jjap96@gmail.com",
    "fecha_nac": "1992-05-12",
    "sexo": "M",
    "direccion": {
      "departamento": "string",
      "ciudad": "string",
      "calle": "string",
      "numero": "string"
    }
  }
}


-----


{
  "img_ci": "string",
  "img_escolaridad": "string",
  "img_carne_salud": "string",
  "sede": {
    "id": 1
  },
  "carrera": {
    "id": 7
  },
  "persona": {
    "cedula": "47101475",
    "nombre": "string",
    "apellido": "string",
    "correo": "jjap96@gmail.com",
    "fecha_nac": "1992-05-12",
    "sexo": "M",
    "direccion": {
      "departamento": "string",
      "ciudad": "string",
      "calle": "string",
      "numero": "string"
    }
  }
}

*/