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
use App\Models\Periodo;
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
            // obtengo la escolaridad con el formato que se muestra al final de este archivo
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
            // obtengo la escolaridad con el formato que se muestra al final de este archivo
            $escolaridad = $this->calcularEscolaridad($ciEstudiante, $idCarrera);

            // convertir $escolaridad a PDF
            // devolverlo de alguna forma

            $datos = [
                'ciEstudiante' => $ciEstudiante,
            ];

            $pdf = PDF::loadView('escolaridad', $datos);

            return $pdf->stream();

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
        // obtengo y verifico Usuario y Carrera
        $usu = Usuario::buscar($ciEstudiante);
        if ($usu == null || $usu->estudiante == null) throw new \Exception("Usuario no encontrado");
        $carrera = Carrera::find($idCarrera);
        if ($carrera == null || $usu->estudiante->inscripcionesCarrera()->where('carrera_id', $idCarrera)->first() == null) throw new \Exception("Carrera no encontrada");
        $cursos = array();
        foreach ($carrera->cursos as $value) $cursos[$value->id] = $value;        

        //----  obtener la informacion --------------------

        // cargo los datos del usuario
        $usu->persona->direccion;

        // sera un array asociativo para asociar la actividad del estudiante al semestre correspondiente
        // key: numero de semestre, value: array de notas
        $semestres = array();
        // obtengo los cursos y examenes a los que se inscribio el estudiante
        $cursosYExamenesTomados = [
            $usu->estudiante->NotasCarrera($idCarrera, false),
            $usu->estudiante->NotasExamenes($idCarrera, false),
        ];

        foreach ($cursosYExamenesTomados as $coet) {
            foreach ($coet as $value) {
                $numSem = $cursos[$value['curso_id']]->pivot->semestre;
                if ( ! array_key_exists($numSem, $semestres)){
                    $semestres[$numSem] = array();
                }
                array_push($semestres[$numSem], $value);
            }
        }

        // calculo el promedio
        // obtengo las actas confirmadas mas recientes y las referencio por el ID del curso
        // y despues hago el calculo
        $notaPromedio = 0;
        $promediar = array();
        foreach ($usu->estudiante->NotasCarrera($idCarrera, true, true) as $value) {
            $promediar[$value['curso_id']] = $value['nota'];
        }
        foreach ($usu->estudiante->NotasExamenes($idCarrera, true, true) as $value) {
            $promediar[$value['curso_id']] = $value['nota'];
        }
        foreach ($promediar as $key => $value) {
            $notaPromedio += $value;
        }
        $count = count($promediar);
        $notaPromedio = $count == 0 ? 0.0 : $notaPromedio / count($promediar);
        $notaPromedio = round($notaPromedio, 2);
        
        //----  formato y limpieza de la informacion --------------------
        
        $escolaridad = [
            "usuario"       => $usu,          // objeto Usuario
            "carrera"       => $carrera,      // objeto Carrera
            "nota_promedio" => $notaPromedio, // nota promedio
            "semestres"     => [],
        ];
        unset($escolaridad['carrera']->cursos);
        unset($escolaridad['usuario']->estudiante);

        foreach ($semestres as $numSem => $detalle) {
            $sem = [
                "numero"  => $numSem, // numero de semestre
                "detalle" => [],
            ];

            foreach ($detalle as $value) {
                $c = $cursos[$value['curso_id']];
                unset($c->pivot);

                $t = "-";
                if (array_key_exists('edicion_curso_id', $value)) $t = 'LE';
                if (array_key_exists('examen_id', $value)) $t = 'EX';
                
                $p = Periodo::find($value['periodo_id'])->toString();
                
                $n = $value['nota'];
                
                $linea = [
                    "curso"   => $c, // objeto Curso
                    "tipo"    => $t, // "EX" si se trata de un examen o "LE" si se trata de un edicion curso
                    "periodo" => $p, // formato: "2019-1S" para periodo lectivo o "2019-Julio" para periodo examen
                    "nota"    => $n, // nota obtenida
                ];
                array_push($sem['detalle'], $linea);
            }

            array_push($escolaridad['semestres'], $sem);
        }
        
        return $escolaridad;
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