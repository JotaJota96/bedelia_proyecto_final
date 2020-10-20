<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\Direccion;
use App\Models\Admin;
use App\Models\Administrativo;
use App\Models\Docente;
use App\Models\Estudiante;

class UsuarioController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * @OA\Post(
     *     path="/usuarios/login",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/LoginDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos del usuario logueado",
     *         @OA\JsonContent(ref="#/components/schemas/LoginResponseDTO"),
     *     ),
     * )
     */
    public function login(){
        $id          = $this->request->input("id");
        $contrasenia = $this->request->input("contrasenia");

        $usu = Usuario::buscar($id);

        // verifica existencia de usuario y su contrasenia
        if ($usu == null || strcmp($contrasenia, $usu->contrasenia) != 0){
            return response()->json(null, 401);
        }
        // para contraseña encriptada
        // if ($usu == null || strcmp($contrasenia, Crypt::decrypt($usu->contrasenia)) != 0){
        //     return response()->json(null, 401);
        // }

        $usu->remember_token = \Illuminate\Support\Str::random(100);
        $usu->save();

        $ret = [
            "cedula" => $usu->persona->cedula,
            "correo" => $usu->persona->correo,
            "token" => $usu->remember_token,
            "roles" => $usu->roles()
        ];
        /**
         * Para las siguientes peticiones se deberá añadir el Header con clave 'Authorization' y valor el token devuelto
         * Para obtener el usuario autenticado dentro de cualquier funcion, usar:
         * $request->user()
         */

        return response()->json($ret, 200);
    }

    /**
     * @OA\Get(
     *     path="/usuarios/{ci}",
     *     tags={"Usuarios"},
     *     @OA\Parameter(
     *         name="ci",
     *         in="path",
     *         description="CI del usuario",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos del usuario",
     *         @OA\JsonContent(ref="#/components/schemas/UsuarioDTO"),
     *     ),
     * )
     */
    public function obtenerUno(String $ci){
        $usu = Usuario::buscar($ci);
        if ($usu == null){
            return response()->json(null, 404);
        }
        $usu->persona->direccion;
        $usu->roles = $usu->roles();

        return response()->json($usu, 200);
    }

    /**
     * @OA\Get(
     *     path="/usuarios/",
     *     tags={"Usuarios"},
     *     @OA\Response(
     *         response=200,
     *         description="Todos los usuarios",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UsuarioDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerTodos(){
        $usuarios = Usuario::all();

        foreach ($usuarios as $Id => $value) {
            $value->persona->direccion;
            $value->roles = $value->roles();
        }
        return response()->json($usuarios, 200);
    }

    /**
     * @OA\Post(
     *     path="/usuarios",
     *     tags={"Usuarios"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/UsuarioDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos del usuario creado",
     *         @OA\JsonContent(ref="#/components/schemas/UsuarioDTO"),
     *     ),
     * )
     */
    public function agregar(){
        try {
            // Para agregar un usuario se recibe: un Usuario, que adentro tiene un objeto Persona, y este adentro tiene un objeto Direccion
            // Creo instancias vacias para cada objeto
            $usu = new Usuario();
            $per = new Persona();
            $dir = new Direccion();

            // Extraigo los datos del JSON
            // el $this->request->json()->all() devuelve un array con todos los datos del JSON que viene en la request
            // el $usu->fill(..) asigna los datos del objeto Usuario extrayendolos de un array asociativo que se le pase por parametro
            $usu->fill($this->request->json()->all());

            // lo mismo que lo anterior pero 'persona' es un objeto dentro del objeto principal
            $per->fill($this->request->json('persona'));
            $usu->contrasenia = $per->cedula;
            // para contraseña encriptada
            //$usu->contrasenia = Crypt::decrypt($usu->contrasenia);

            // obtengo los roles (son un simple array de strings)
            $roles = $this->request->json('roles');

            // lo mismo que lo anterior pero 'direccion' es un objeto dentro del objeto 'persona' que viene dentro del objeto principal
            $dir->fill($this->request->json(['persona', 'direccion']));

            // Los objetos instanciados al principio ya tienen los valores recibidos en la peticion pero no estan asociados entre si
            // La asociacion debe aserse mientras se van guardando en la DB porque se necesitan los ID autoincrementales

            // Asociacion entre usuario y persona
            $usu->persona()->associate($per);
            // Asociacion entre la persona asociada y direccion
            $usu->persona->direccion()->associate($dir);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error al procesar los datos recibidos. $e->getMessage()"], 500);
        }

        // en este caso hay que insertar varias cosas a la vez, asi que hay que usar una transaccion
        // para que funcione el "BD::" hay que agregar el "use Illuminate\Support\Facades\DB;" al principio del archivo
        try {
            DB::beginTransaction();

            // inserta la direccion y se la asocia a la persona
            $dir->save();
            $per->direccion()->associate($dir);

            // inserta la persona y se la asocia al usuario
            $per->save();
            $usu->persona()->associate($per);

            // inserta el usuario
            $usu->save();

            // si no se especifica ningun rol, lanza una excepcion, sino inserta segun los roles
            if (count($roles) < 1){
                $x = 2/0; // no se como lanzar una excepcion, asi que pongo esto
            }else{
                if (in_array("admin", $roles)) {
                    $admin = new Admin();
                    $admin->usuario()->associate($usu);
                    $admin->save();
                }
                if (in_array("administrativo", $roles)) {
                    $adminis = new Administrativo();
                    $adminis->usuario()->associate($usu);
                    $adminis->save();
                }
                if (in_array("docente", $roles)) {
                    $doc = new Docente();
                    $doc->usuario()->associate($usu);
                    $doc->save();
                }
                if (in_array("estudiante", $roles)) {
                    $est = new Estudiante();
                    $est->usuario()->associate($usu);
                    $est->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // devuelve un estado HTTP 500 y un mensaje simple del error
            return response()->json(["message" => "Error al guardar los datos. $e->getMessage()"], 500);
        }

        $usu->roles = $usu->roles();

        // se envia un correo al nuevo usuario
        $mailData = [
            'destinatario' => $usu->persona->correo,
            'nombre'       => $usu->persona->nombre,
            'usuario'      => $usu->persona->cedula,
            'contrasenia'  => $usu->contrasenia,
        ];
        \App\Mail\CorreoBienvenida::enviar($mailData);

        // si no hubo nngun problema, devuelve el usuario (que ya tiene encadenado la persona y la direccion)
        return response()->json($usu, 200);
    }

    
    /**
     * @OA\Get(
     *     path="/usuarios/docentes",
     *     tags={"Usuarios"},
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UsuarioDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerDocentes(){
        // devuelve todos los usuarios con rol 'docente'
        // revisar en el modelo \App\Models\Usuario que que hay una funcion que devuelve un string[] con los roles
        $Docentes = docente::all();
        $usus = array();
        foreach ($Docentes as $id => $Docente) {
            $Docente->usuario->persona->direccion;
            array_push($usus, $Docente->usuario);
        }

        return response()->json($usus, 200);
    }
    /**
     * @OA\Put(
     *     path="/usuarios/passReset",
     *     tags={"Usuarios"},
     *     description="Actualiza la contraseña del usuario",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/LoginDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos del usuario logueado",
     *         @OA\JsonContent(ref="#/components/schemas/LoginResponseDTO"),
     *     ),
     * )
     */
    public function cambiarContrasenia(){
        $id          = $this->request->input("id");
        $contrasenia = $this->request->input("contrasenia");

        $usu = Usuario::buscar($id);

        // verifica existencia de usuario y su contrasenia
        if ($usu == null){
            return response()->json(null, 401);
        }
        $usu->contrasenia = $contrasenia;
        // para contraseña encriptada
        //$usu->contrasenia = Crypt::decrypt($usu->contrasenia);

        $usu->remember_token = \Illuminate\Support\Str::random(100);
        $usu->save();

        $ret = [
            "cedula" => $usu->persona->cedula,
            "correo" => $usu->persona->correo,
            "token" => $usu->remember_token,
            "roles" => $usu->roles()
        ];
        /**
         * Para las siguientes peticiones se deberá añadir el Header con clave 'Authorization' y valor el token devuelto
         * Para obtener el usuario autenticado dentro de cualquier funcion, usar:
         * $request->user()
         */

        return response()->json($ret, 200);
    }


}
