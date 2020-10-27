<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Examen;
use App\Models\Carrera;
use App\Models\Curso;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Estudiante;

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
            $escolaridad = $this->calcularEscolaridad($ciEstudiante, $idCarrera);
            return response()->json($escolaridad, 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
    }
    
    /**
     * @OA\Get(
     *     path="/estudiantes/{ciEstudiante}/escolaridad/{idCarrera}/pdf",
     *     tags={"Estudiantes"},
     *     description="Devuelve la escolaridad de un estudiante como PDF",
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
            $escolaridad = $this->calcularEscolaridad($ciEstudiante, $idCarrera);


            //return response()->json($escolaridad, 200);
            return response()->json(['message' => 'No implementado aun'], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
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
            return response()->json(['message' => 'No implementado aun'], 500);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
    }
    
    private function calcularEscolaridad($ciEstudiante, $idCarrera){
        return $this->fooEscolaridad("00000000", 1);
    }

    private function fooEscolaridad($ciEstudiante, $idCarrera){
        return [
            "usuario"       => Usuario::buscar($ciEstudiante),
            "nota_promedio" => 4.3,
            "semestres"     => [
                [
                    "numero"  => 1,
                    "detalle" => [
                        [
                            "curso"   => Curso::find(1),
                            "tipo"    => "LE",
                            "periodo" => "2018-1S",
                            "nota"    => 3.6,
                        ], [
                            "curso"   => Curso::find(2),
                            "tipo"    => "LE",
                            "periodo" => "2018-1S",
                            "nota"    => 2.6,
                        ], [
                            "curso"   => Curso::find(2),
                            "tipo"    => "EX",
                            "periodo" => "2018-Julio",
                            "nota"    => 2.6,
                        ],
                        // [], []
                    ],
                ], [
                    "numero"  => 2,
                    "detalle" => [
                        [
                            "curso"   => Curso::find(4),
                            "tipo"    => "LE",
                            "periodo" => "2018-2S",
                            "nota"    => 4.8,
                        ], [
                            "curso"   => Curso::find(5),
                            "tipo"    => "LE",
                            "periodo" => "2018-2S",
                            "nota"    => 3.67,
                        ], [
                            "curso"   => Curso::find(2),
                            "tipo"    => "EX",
                            "periodo" => "2018-Diciembre",
                            "nota"    => 3.1,
                        ],
                        // [], []
                    ],
                ], 
                // [], []
            ],
        ];
    }

}
/*
// Formato para devolver y trabajar las escolaridades
// 
// $escolaridad = [
//     "usuario"       => null, // objeto Usuario
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