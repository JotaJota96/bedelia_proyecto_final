<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Carrera;
use App\Models\Escolaridad;
use App\Models\Usuario;
use Barryvdh\DomPDF\Facade as PDF;

class EstudianteController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * @OA\Get(
     *     path="/estudiantes/{ciEstudiante}/carreras",
     *     tags={"Estudiantes"},
     *     description="Devuelve las carreras a las que está inscripto el estudiante",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="ciEstudiante",
     *         in="path",
     *         description="CI del estudiante",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve un usuario correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/CarreraDTO"),
     *     ),
     * )
     */
    public function Careras($ciEstudiante){
        try {
            $RES = [];
            $Estudiante = Usuario::buscar($ciEstudiante);
            $Estudiante=$Estudiante->estudiante;
            foreach ($Estudiante->inscripcionesCarrera as $inscripcionCarrera) {
                array_push($RES, $inscripcionCarrera->carrera);
            }
            return response()->json($RES, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al buscar las carreras.' . $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/estudiantes/{ciEstudiante}/asistencias",
     *     tags={"Estudiantes"},
     *     description="justifica las inasistencias del estudiante para las clases dictadas dentro del rango de fechas",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="ciEstudiante",
     *         in="path",
     *         description="CI del estudiante",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="fecha_inicio", type="string"),
     *              @OA\Property(property="fecha_fin",    type="string"),
     *          ),
     *      ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     ),
     * )
     */
    public function JustificarInasistencias($ciEstudiante){
        try {
            $RES = [];
            $Estudiante = Usuario::buscar($ciEstudiante);
            $Estudiante=$Estudiante->estudiante;
            $fecha_inicio = $this->request->json('fecha_inicio');
            $fecha_fin = $this->request->json('fecha_fin');
            $clasesDictadas = $Estudiante->clasesDictada->where('fecha', '>=', $fecha_inicio)->where('fecha', '<=', $fecha_fin);
            foreach ($clasesDictadas as $claseDictada) {
                if ($claseDictada->pivot->asistencia == 0){
                    $claseDictada->pivot->asistencia = 1;
                    $claseDictada->pivot->save();
                }
            }
            return response()->json(null, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al justificar inasistencias.' . $e->getMessage()], 500);
        }
    }
    // public function NotaCurso($idCurso, $ciEstudiante){
    //     try {
    //         DB::beginTransaction();
    //         $Examen = Examen::where('id', $id)->first();
    //         $Usuario = Usuario::buscar($ciEstudiante);
    //         $Usuario->estudiante;
    //         $datosTablaIntermedia = [
    //             'nota' => 0,
    //         ];
    //         $Usuario->estudiante->examenes()->attach($Examen, $datosTablaIntermedia);
    //         $Usuario->save();
    //         DB::commit();
    //         return response()->json(null, 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
    //     }
    // }

    // public function NotaCursos($ciEstudiante){
    //     try {
    //         DB::beginTransaction();
    //         $Examen = Examen::where('id', $id)->first();
    //         $Usuario = Usuario::buscar($ciEstudiante);
    //         $Usuario->estudiante;
    //         $datosTablaIntermedia = [
    //             'nota' => 0,
    //         ];
    //         $Usuario->estudiante->examenes()->attach($Examen, $datosTablaIntermedia);
    //         $Usuario->save();
    //         DB::commit();
    //         return response()->json(null, 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
    //     }
    // }
    

    /**
     * @OA\Get(
     *     path="/estudiantes/{ciEstudiante}/escolaridad/{idCarrera}",
     *     tags={"Estudiantes"},
     *     description="Devuelve la escolaridad de un estudiante para ser mostraa en frontend",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="ciEstudiante",
     *         in="path",
     *         description="CI del estudiante",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="idCarrera",
     *         in="path",
     *         description="ID de la carrera",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Datos de la escolaridad del estudiante",
     *         @OA\JsonContent(ref="#/components/schemas/EscolaridadDTO"),
     *     ),
     * )
     */
    public function obtenerEscolaridad($ciEstudiante, $idCarrera){
        // Devuelve la escolaridad de un estudiante para ser mostraa en frontend
        try {
            // obtengo y verifico Usuario Estudiante y Carrera
            $usu = Usuario::buscar($ciEstudiante);
            if ($usu == null || $usu->estudiante == null){
                return response()->json(['message' => 'Usuario no encontrado.'], 404);
            }
            if (Carrera::find($idCarrera) == null){
                return response()->json(['message' => 'Carrera no encontrada.'], 404);
            }

            // obtengo la escolaridad con el formato que se muestra al final de este archivo
            $escolaridad = $usu->estudiante->calcularEscolaridad($idCarrera);
            
            return response()->json($escolaridad, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener la escolaridad. ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * @OA\Get(
     *     path="/estudiantes/{ciEstudiante}/escolaridad/{idCarrera}/pdf",
     *     tags={"Estudiantes"},
     *     description="Devuelve la escolaridad de un estudiante como PDF",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="ciEstudiante",
     *         in="path",
     *         description="CI del estudiante",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="idCarrera",
     *         in="path",
     *         description="ID de la carrera",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Todavía no se ha definido el objeto"
     *     ),
     * )
     */
    public function obtenerEscolaridadPDF($ciEstudiante, $idCarrera){
        // Devuelve la escolaridad de un estudiante como PDF
        try {
            // obtengo y verifico Usuario Estudiante y Carrera
            $usu = Usuario::buscar($ciEstudiante);
            if ($usu == null || $usu->estudiante == null){
                return response()->json(['message' => 'Usuario no encontrado.'], 404);
            }
            if (Carrera::find($idCarrera) == null){
                return response()->json(['message' => 'Carrera no encontrada.'], 404);
            }

            // ---- ---- obtengo la escolaridad con el formato que se muestra al final de este archivo ---- ----
            $escolaridad = $usu->estudiante->calcularEscolaridad($idCarrera);
            
            // ---- ---- procesamiento de datos para mostrar en PDF ---- ----
            // le agrego 1 mes a la fecha actual
            $fechaValidez = date("d/m/Y", strtotime("+1 month"));
            
            // genero codigos hasta que no se repita
            $codigoVerificacion = "";
            do {
                $codigoVerificacion = Str::random(4) . "-" . Str::random(4) . "-" . Str::random(8);
                $codigoVerificacion = strtoupper($codigoVerificacion);
            } while (Escolaridad::find($codigoVerificacion) != null);

            // ---- ---- agrego datos a mostrar en el PDF ---- ----
            $escolaridad['fecha']              = date('d/m/Y');            // fecha de emision
            $escolaridad['codigoVerificacion'] = $codigoVerificacion;      // código identificativo para verificacion
            $escolaridad['fechaValidez']       = $fechaValidez;            // fecha limite para verificacion
            $escolaridad['urlVerificar']       = env("WEB_URL_VERIFICAR"); //URL de la pagina para verificar

            // descomentar el return para ver como pagina web
            //return view('escolaridad', $escolaridad);

            // ---- ---- Renderizo la escolaridad y obtengo su codigo HTML
            $html = view('escolaridad', $escolaridad)->render();

            // ---- ---- guardo escolaridad en la base de datos
            DB::beginTransaction();
            $esc = new Escolaridad();
            $esc->clave   = $codigoVerificacion;
            $esc->archivo = base64_encode($html);
            $esc->fecha   = date("Y-m-d", strtotime("+1 month"));
            $esc->save();

            // renderizo el HTML como PDF
            $pdf = PDF::loadHtml($html);

            // si se renderizo bien, hago el commit y lo devuelvo
            DB::commit();
            return $pdf->download();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al obtener la escolaridad. ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/estudiantes/escolaridad/{codigo}",
     *     tags={"Estudiantes"},
     *     description="Devuelve la escolaridad basandose en su código de identificacion",
     *     @OA\Parameter(
     *         name="codigo",
     *         in="path",
     *         description="Código de la escolaridad",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Todavía no se ha definido el objeto"
     *     ),
     * )
     */
    public function verificarEscolaridad($codigo){
        // Devuelve la escolaridad basandose en su código de identificacion
        try {
            $esc = Escolaridad::where("clave", $codigo)->where("fecha", ">=", date("Y-m-d"))->first();
            if ($esc == null){
                return response()->json(['message' => 'Escolaridad no encontrada.'], 404);
            }

            // decodifico el HTML almacenado
            $html = base64_decode($esc->archivo);

            $pdf = PDF::loadHtml($html);
            
            return $pdf->download();

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener la escolaridad. ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * @OA\Get(
     *     path="/estudiantes/escolaridad/{codigo}/existe",
     *     tags={"Estudiantes"},
     *     description="Verifica si existe una escolaridad con ese codigo de verificacion",
     *     @OA\Parameter(
     *         name="codigo",
     *         in="path",
     *         description="Código de la escolaridad",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Todavía no se ha definido el objeto"
     *     ),
     * )
     */
    public function verificarCodigoEscolaridad($codigo){
        // Devuelve la escolaridad basandose en su código de identificacion
        try {
            $esc = Escolaridad::where("clave", $codigo)->where("fecha", ">=", date("Y-m-d"))->first();
            if ($esc == null){
                return response()->json(['message' => 'Escolaridad no encontrada.'], 404);
            }else{
                return response()->json(null, 200);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener la escolaridad. ' . $e->getMessage()], 500);
        }
    }
}
/*
// Formato para devolver y trabajar las escolaridades
// 
// $escolaridad = [
//     "usuario"       => null, // objeto Usuario
//     "carrera"       => null, // objeto Carrera
//     "nota_promedio" => 0.0, // nota promedio
//     "semestres"       => [
//         [
//             "numero"  => 0, // numero de semestre
//             "detalle" => [
//                 [
//                     "curso"   => null, // objeto Curso
//                     "tipo"    => "",   // "EX" si se trata de un examen o "LE" si se trata de un edicion curso
//                     "periodo" => "",   // formato: "2019-1S" para periodo lectivo o "2019-Julio" para periodo examen
//                     "nota"    => 0.0,  // nota obtenida
//                 ], 
//                 // [], []
//             ],
//         ], 
//         // [], []
//     ],
// ];
*/