<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
    * @OA\Info(
    *     title="API Bedelia UTEC",
    *     version="1.0",
    *     @OA\Contact(
    *         email="support@example.com",
    *         name="Support Team"
    *     )
    * )
    */

    /**
     * @OA\SecurityScheme(
     *     securityScheme="api_key",
     *     type="apiKey",
     *     in="header",
     *     name="Authorization",
     *     description="Token de autenticacion conformato como '<token>'",
     * )
     */

    //** Objetos de la API ******************************************************* */
    
    /**
     * @OA\Schema(
     *     schema="LoginDTO",
     *     @OA\Property(property="id",          type="string"),
     *     @OA\Property(property="contrasenia", type="string"),
     * )
     */

    /**
     * @OA\Schema(
     *     schema="LoginResponseDTO",
     *     @OA\Property(property="cedula", type="string"),
     *     @OA\Property(property="correo", type="string"),
     *     @OA\Property(property="roles", type="string[]"),
     * )
     */

    /**
     * @OA\Schema(
     *     schema="DireccionDTO",
     *     @OA\Property(property="departamento", type="string"),
     *     @OA\Property(property="ciudad",       type="string"),
     *     @OA\Property(property="calle",        type="string"),
     *     @OA\Property(property="numero",       type="string"),
     * )
     */

    /**
     * @OA\Schema(
     *     schema="PersonaDTO",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="cedula", type="string"),
     *     @OA\Property(property="nombre", type="string"),
     *     @OA\Property(property="apellido", type="string"),
     *     @OA\Property(property="correo", type="string"),
     *     @OA\Property(property="fecha_nac", type="string"),
     *     @OA\Property(property="sexo", type="string"),
     *     @OA\Property(
     *         property="direccion",
     *         ref="#/components/schemas/DireccionDTO",
     *     ),
     * )
     */

     /**
     * @OA\Schema(
     *     schema="UsuarioDTO",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="contrasenia", type="string"),
     *     @OA\Property(
     *         property="persona",
     *         ref="#/components/schemas/PersonaDTO",
     *     ),
     * )
     */

}
