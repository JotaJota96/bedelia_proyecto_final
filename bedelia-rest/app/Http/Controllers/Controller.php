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
     *     @OA\Property(property="token",  type="string"),
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
     *     @OA\Property(property="id",               type="integer"),
     *     @OA\Property(property="contrasenia",      type="string"),
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
     *     @OA\Property(property="id",       type="integer"),
     *     @OA\Property(property="area",     type="string"),
     *     @OA\Property(property="creditos", type="integer", description="Depende de la carrera"),
     * )
     */

     /**
     * @OA\Schema(
     *     schema="CursoDTO",
     *     @OA\Property(property="id",                type="integer"),
     *     @OA\Property(property="nombre",            type="string"),
     *     @OA\Property(property="descripcion",       type="string"),
     *     @OA\Property(property="max_inasistencias", type="integer"),
     *     @OA\Property(property="cant_creditos",     type="integer"),
     *     @OA\Property(property="cant_clases",       type="integer"),
     *     @OA\Property(property="semestre",          type="integer", description="Depende de la carrera"),
     *     @OA\Property(property="optativo",          type="integer", description="Depende de la carrera"),
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
     *         @OA\Items(ref="#/components/schemas/AreaEstudioDTO"),
     *     ),
     *     @OA\Property(
     *         property="sedes",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/SedeDTO"),
     *     ),
     * )
     */

    /**
     * @OA\Schema(
     *     schema="PreviaDTO",
     *     @OA\Property(property="id",               type="integer"),
     *     @OA\Property(property="carrera_id",       type="integer"),
     *     @OA\Property(property="curso_id",         type="integer"),
     *     @OA\Property(property="curso_id_previa",  type="integer"),
     *     @OA\Property(property="tipo",             type="string"),
     * )
     */


    /**
     * @OA\Schema(
     *     schema="CarreraCreateDTO",
     *     @OA\Property(property="id",              type="integer"),
     *     @OA\Property(property="nombre",          type="string"),
     *     @OA\Property(property="descripcion",     type="string"),
     *     @OA\Property(property="cant_semestres",  type="integer"),
     *     @OA\Property(
     *         property="sedes",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/SedeDTO"),
     *     ),
     *     @OA\Property(
     *         property="areas_estudio",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/AreaEstudioDTO"),
     *     ),
     *     @OA\Property(
     *         property="previas",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/PreviaDTO"),
     *     ),
     *     @OA\Property(
     *         property="cursos",
     *         type="array",
     *         @OA\Items(
     *             @OA\Property(property="id",         type="integer"),
     *             @OA\Property(property="semestre",   type="integer"),
     *             @OA\Property(property="optativo",   type="boolean"),
     *         ),
     *     ),
     * )
     */


    /**
     * @OA\Schema(
     *     schema="PostulanteDTO",
     *     @OA\Property(property="id",              type="integer"),
     *     @OA\Property(property="img_ci",          type="string"),
     *     @OA\Property(property="img_escolaridad", type="string"),
     *     @OA\Property(property="img_carne_salud", type="string"),
     *     @OA\Property(
     *         property="sede",
     *         ref="#/components/schemas/SedeDTO",
     *     ),
     *     @OA\Property(
     *         property="carrera",
     *         ref="#/components/schemas/CarreraDTO",
     *     ),
     *     @OA\Property(
     *         property="persona",
     *         ref="#/components/schemas/PersonaDTO",
     *     ),
     * )
     */
    
    /**
     * @OA\Schema(
     *     schema="EdicionCursoDTO",
     *     @OA\Property(property="id",              type="integer"),
     *     @OA\Property(property="acta_confirmada", type="boolean"),
     *     @OA\Property(property="habilitado",      type="integer", readOnly=true, description="1 = el estudiante está habilitado para inscribirse, 0 = curso ya aprobado, -1 = no se cumple con las previas"),
     *     @OA\Property(
     *         property="sede",
     *         ref="#/components/schemas/SedeDTO",
     *     ),
     *     @OA\Property(
     *         property="curso",
     *         ref="#/components/schemas/CursoDTO",
     *     ),
     *     @OA\Property(
     *         property="docente",
     *         ref="#/components/schemas/UsuarioDTO",
     *     ),
     * )
     */


    /**
     * @OA\Schema(
     *     schema="ExamenDTO",
     *     @OA\Property(property="id",              type="integer"),
     *     @OA\Property(property="fecha",           type="string"),
     *     @OA\Property(property="acta_confirmada", type="boolean"),
     *     @OA\Property(property="habilitado",      type="integer", readOnly=true, description="1 = el estudiante está habilitado para inscribirse, 0 = no es necesario dar el examen, -1 = no ha ganado el derecho a dar examen"),
     *     @OA\Property(
     *         property="sede",
     *         ref="#/components/schemas/SedeDTO",
     *     ),
     *     @OA\Property(
     *         property="curso",
     *         ref="#/components/schemas/CursoDTO",
     *     ),
     *     @OA\Property(
     *         property="docente",
     *         ref="#/components/schemas/UsuarioDTO",
     *     ),
     * )
     */


    /**
     * @OA\Schema(
     *     schema="ClaseDictadaDTO",
     *     @OA\Property(property="id",              type="integer", readOnly=true),
     *     @OA\Property(property="fecha",           type="string", readOnly=true),
     *     @OA\Property(property="curso_id",        type="integer", readOnly=true),
     *     @OA\Property(property="edicion_curso_id",type="integer", readOnly=true),
     *     @OA\Property(
     *         property="lista",
     *         type="array",
     *         @OA\Items(
     *             @OA\Property(property="ciEstudiante",         type="string"),
     *             @OA\Property(property="nombre",               type="string", readOnly=true),
     *             @OA\Property(property="apellido",             type="string", readOnly=true),
     *             @OA\Property(property="asistencia",           type="number", writeOnly=true),
     *             @OA\Property(property="total_asistencias",    type="number", readOnly=true),
     *             @OA\Property(property="total_llegadas_tarde", type="number", readOnly=true),
     *             @OA\Property(property="total_inasistencias",  type="number", readOnly=true),
     *         ),
     *     ),
     * )
     */


    /**
     * @OA\Schema(
     *     schema="ActaDTO",
     *     @OA\Property(property="id",     type="integer", readOnly=true, description="ID del Examen o EdicionCurso (segun tipo"),
     *     @OA\Property(property="tipo",   type="string",  readOnly=true, description="EX = examen, LE = cursos"),
     *     @OA\Property(property="fecha",  type="string",  readOnly=true, description="Fecha del examen (si tipo = EX)"),
     *     @OA\Property(
     *         property="notas",
     *         type="array",
     *         @OA\Items(
     *             @OA\Property(property="ciEstudiante", type="string"),
     *             @OA\Property(property="nombre",       type="string", readOnly=true),
     *             @OA\Property(property="apellido",     type="string", readOnly=true),
     *             @OA\Property(property="nota",         type="number"),
     *         ),
     *     ),
     * )
     */

     
    /**
     * @OA\Schema(
     *     schema="EscolaridadDTO",
     *     @OA\Property(
     *         property="usuario",
     *         ref="#/components/schemas/UsuarioDTO",
     *     ),
     *     @OA\Property(
     *         property="carrera",
     *         ref="#/components/schemas/CarreraDTO",
     *     ),
     *     @OA\Property(property="nota_promedio", type="number"),
     *     @OA\Property(
     *         property="semestres",
     *         type="array",
     *         @OA\Items(
     *             @OA\Property(property="numero", type="integer"),
     *             @OA\Property(
     *                 property="detalle",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="curso",
     *                         ref="#/components/schemas/CursoDTO"
     *                     ),
     *                     @OA\Property(property="tipo",    type="string"),
     *                     @OA\Property(property="periodo", type="string"),
     *                     @OA\Property(property="nota",    type="number"),
     *                 ),
     *             ),
     *         ),
     *     ),
     * )
     */

    /**
     * @OA\Post(
     *     path="/foo",
     *     tags={"Foo"},
    *      @OA\RequestBody(
    *          @OA\JsonContent(
    *              @OA\Property(property="fecha_inicio", type="string"),
    *              @OA\Property(property="fecha_fin",    type="string"),
    *          ),
    *      ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/CursoDTO"),
     *     ),
     * )
     */




}


