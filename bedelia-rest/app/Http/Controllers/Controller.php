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


}
