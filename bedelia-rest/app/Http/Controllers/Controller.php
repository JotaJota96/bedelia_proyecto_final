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
     *     @OA\Property(
     *         property="roles",
     *         type="array",
     *         @OA\Items(type="string"),
     *     ),
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
     *         property="roles",
     *         type="array",
     *         @OA\Items(type="string"),
     *     ),
     *     @OA\Property(
     *         property="persona",
     *         ref="#/components/schemas/PersonaDTO",
     *     ),
     * )
     */
    
    /**
     * @OA\Schema(
     *     schema="SedeDTO",
     *     @OA\Property(property="id",       type="integer"),
     *     @OA\Property(property="telefono", type="string"),
     *     @OA\Property(
     *         property="direccion",
     *         ref="#/components/schemas/DireccionDTO",
     *     ),
     * )
     */
    

    /**
     * @OA\Schema(
     *     schema="TipoCursoDTO",
     *     @OA\Property(property="id",   type="integer"),
     *     @OA\Property(property="tipo", type="string"),
     * )
     */

    /**
     * @OA\Schema(
     *     schema="AreaEstudioDTO",
     *     @OA\Property(property="id",   type="integer"),
     *     @OA\Property(property="area", type="string"),
     * )
     * 
     * @OA\Schema(
     *     schema="AreaEstudioCarreraDTO",
     *     @OA\Property(property="id",       type="integer"),
     *     @OA\Property(property="area",     type="string"),
     *     @OA\Property(property="creditos", type="integer"),
     * )
     */

     /**
     * @OA\Schema(
     *     schema="CursoDTO",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="nombre", type="string"),
     *     @OA\Property(property="descripcion", type="string"),
     *     @OA\Property(property="max_inasistencias", type="integer"),
     *     @OA\Property(property="cant_creditos", type="integer"),
     *     @OA\Property(property="cant_clases", type="integer"),
     *     @OA\Property(
     *         property="area_estudio",
     *         ref="#/components/schemas/AreaEstudioDTO",
     *     ),
     *     @OA\Property(
     *         property="tipo_curso",
     *         ref="#/components/schemas/TipoCursoDTO",
     *     ),
     * )
     */

    /**
     * @OA\Schema(
     *     schema="AnioLectivoDTO",
     *     @OA\Property(property="ini_1er_per_insc_exam", type="string", example="2018-01-01"),
     *     @OA\Property(property="fin_1er_per_insc_exam", type="string", example="2018-01-15"),
     *     @OA\Property(property="ini_1er_per_exam",      type="string", example="2018-02-01"),
     *     @OA\Property(property="fin_1er_per_exam",      type="string", example="2018-02-15"),
     *     @OA\Property(property="ini_1er_per_insc_lect", type="string", example="2018-03-01"),
     *     @OA\Property(property="fin_1er_per_insc_lect", type="string", example="2018-03-15"),
     *     @OA\Property(property="ini_1er_per_lect",      type="string", example="2018-04-01"),
     *     @OA\Property(property="fin_1er_per_lect",      type="string", example="2018-04-15"),
     *     @OA\Property(property="ini_2do_per_insc_exam", type="string", example="2018-05-01"),
     *     @OA\Property(property="fin_2do_per_insc_exam", type="string", example="2018-05-15"),
     *     @OA\Property(property="ini_2do_per_exam",      type="string", example="2018-06-01"),
     *     @OA\Property(property="fin_2do_per_exam",      type="string", example="2018-06-15"),
     *     @OA\Property(property="ini_2do_per_insc_lect", type="string", example="2018-07-01"),
     *     @OA\Property(property="fin_2do_per_insc_lect", type="string", example="2018-07-15"),
     *     @OA\Property(property="ini_2do_per_lect",      type="string", example="2018-08-01"),
     *     @OA\Property(property="fin_2do_per_lect",      type="string", example="2018-08-15"),
     *     @OA\Property(property="ini_3er_per_insc_exam", type="string", example="2018-09-01"),
     *     @OA\Property(property="fin_3er_per_insc_exam", type="string", example="2018-09-15"),
     *     @OA\Property(property="ini_3er_per_exam",      type="string", example="2018-10-01"),
     *     @OA\Property(property="fin_3er_per_exam",      type="string", example="2018-10-15"),
     * )
     */
    
    /**
     * @OA\Schema(
     *     schema="CarreraDTO",
     *     @OA\Property(property="id",              type="integer"),
     *     @OA\Property(property="nombre",          type="string"),
     *     @OA\Property(property="descripcion",     type="string"),
     *     @OA\Property(property="cant_semestres",  type="integer"),
     *     @OA\Property(
     *         property="areas_estudio",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/AreaEstudioCarreraDTO"),
     *     ),
     *     @OA\Property(
     *         property="sedes",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/SedeDTO"),
     *     ),
     * )
     */

    /**
     * @OA\Post(
     *     path="/foo",
     *     tags={"Foo"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/AnioLectivoDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/CursoDTO"),
     *     ),
     * )
     */

}
