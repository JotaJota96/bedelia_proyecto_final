<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Usuario;

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
}
