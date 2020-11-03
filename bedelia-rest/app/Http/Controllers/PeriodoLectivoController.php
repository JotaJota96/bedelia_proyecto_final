<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Periodo;
use App\Models\PeriodoLectivo;
use App\Models\PeriodoInscExamen;
use App\Models\PeriodoExamen;
use App\Models\PeriodoInscCurso;
use App\Models\Sede;
use App\Models\Examen;
use App\Models\EdicionCurso;

class PeriodoLectivoController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }
    
    /**
     * @OA\Get(
     *     path="/periodos",
     *     tags={"Periodos"},
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve el ultimo anio lectivo ingresado",
     *         @OA\JsonContent(ref="#/components/schemas/AnioLectivoDTO"),
     *     ),
     * )
     */
    public function obtenerLista(){
        try {

            $PeriodoInscExamen1 = Periodo::where('numero', 1)->where('tipo', 'IE')->orderby('id', 'desc')->first();
            $PeriodoExamen1     = Periodo::where('numero', 1)->where('tipo', 'EX')->orderby('id', 'desc')->first();
            $PeriodoInscCurso1  = Periodo::where('numero', 1)->where('tipo', 'IC')->orderby('id', 'desc')->first();
            $PeriodoLectivo1    = Periodo::where('numero', 1)->where('tipo', 'LE')->orderby('id', 'desc')->first();

            $PeriodoInscExamen2 = Periodo::where('numero', 2)->where('tipo', 'IE')->orderby('id', 'desc')->first();
            $PeriodoExamen2     = Periodo::where('numero', 2)->where('tipo', 'EX')->orderby('id', 'desc')->first();
            $PeriodoInscCurso2  = Periodo::where('numero', 2)->where('tipo', 'IC')->orderby('id', 'desc')->first();
            $PeriodoLectivo2    = Periodo::where('numero', 2)->where('tipo', 'LE')->orderby('id', 'desc')->first();

            $PeriodoInscExamen3 = Periodo::where('numero', 3)->where('tipo', 'IE')->orderby('id', 'desc')->first();
            $PeriodoExamen3     = Periodo::where('numero', 3)->where('tipo', 'EX')->orderby('id', 'desc')->first();

            $items = array(
                "ini_1er_per_insc_exam" => $PeriodoInscExamen1->fecha_inicio,
                "fin_1er_per_insc_exam" => $PeriodoInscExamen1->fecha_fin,
                "ini_1er_per_exam"      => $PeriodoExamen1->fecha_inicio,
                "fin_1er_per_exam"      => $PeriodoExamen1->fecha_fin,
                "ini_1er_per_insc_lect" => $PeriodoInscCurso1->fecha_inicio,
                "fin_1er_per_insc_lect" => $PeriodoInscCurso1->fecha_fin,
                "ini_1er_per_lect"      => $PeriodoLectivo1->fecha_inicio,
                "fin_1er_per_lect"      => $PeriodoLectivo1->fecha_fin,

                "ini_2do_per_insc_exam" => $PeriodoInscExamen2->fecha_inicio,
                "fin_2do_per_insc_exam" => $PeriodoInscExamen2->fecha_fin,
                "ini_2do_per_exam"      => $PeriodoExamen2->fecha_inicio,
                "fin_2do_per_exam"      => $PeriodoExamen2->fecha_fin,
                "ini_2do_per_insc_lect" => $PeriodoInscCurso2->fecha_inicio,
                "fin_2do_per_insc_lect" => $PeriodoInscCurso2->fecha_fin,
                "ini_2do_per_lect"      => $PeriodoLectivo2->fecha_inicio,
                "fin_2do_per_lect"      => $PeriodoLectivo2->fecha_fin,
                
                "ini_3er_per_insc_exam" => $PeriodoInscExamen3->fecha_inicio,
                "fin_3er_per_insc_exam" => $PeriodoInscExamen3->fecha_fin,
                "ini_3er_per_exam"      => $PeriodoExamen3->fecha_inicio,
                "fin_3er_per_exam"      => $PeriodoExamen3->fecha_fin
            );
            // $versiones = array("productos" => array($items));
            json_encode($items);     
            
            return response()->json($items, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/periodos",
     *     tags={"Periodos"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/AnioLectivoDTO"),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="No devuelve nada, ya es bastante que funcione xD",
     *     ),
     * )
     */
    public function agregar(){
        try {
            DB::beginTransaction();
            
            $periodosExamen   = array();
            $periodosLectivos = array();

            $Periodo = new Periodo();
            $PeriodoInscExamen = new PeriodoInscExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_1er_per_insc_exam');
            $Periodo->fecha_fin    = $this->request->json('fin_1er_per_insc_exam');
            $Periodo->tipo         = 'IE';
            $Periodo->numero       = 1;
            $Periodo->save();
            $PeriodoInscExamen->periodo()->associate($Periodo);
            $PeriodoInscExamen->save();
            
            $Periodo = new Periodo();
            $PeriodoExamen = new PeriodoExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_1er_per_exam');
            $Periodo->fecha_fin    = $this->request->json('fin_1er_per_exam');
            $Periodo->tipo         = 'EX';
            $Periodo->numero       = 1;
            $Periodo->save();
            $PeriodoExamen->periodo()->associate($Periodo);
            $PeriodoExamen->save();
            $PeriodoExamen->id = $Periodo->id;
            
            array_push($periodosExamen, $PeriodoExamen);

            $Periodo = new Periodo();
            $PeriodoInscCurso = new PeriodoInscCurso();
            $Periodo->fecha_inicio = $this->request->json('ini_1er_per_insc_lect');
            $Periodo->fecha_fin    = $this->request->json('fin_1er_per_insc_lect');
            $Periodo->tipo         = 'IC';
            $Periodo->numero       = 1;
            $Periodo->save();
            $PeriodoInscCurso->periodo()->associate($Periodo);
            $PeriodoInscCurso->save();
            
            $Periodo = new Periodo();
            $PeriodoLectivo = new PeriodoLectivo();
            $Periodo->fecha_inicio = $this->request->json('ini_1er_per_lect');
            $Periodo->fecha_fin    = $this->request->json('fin_1er_per_lect');
            $Periodo->tipo         = 'LE';
            $Periodo->numero       = 1;
            $Periodo->save();
            $PeriodoLectivo->periodo()->associate($Periodo);
            $PeriodoLectivo->save();
            $PeriodoLectivo->id = $Periodo->id;

            array_push($periodosLectivos, $PeriodoLectivo);
            
            //2-----------------------------------------------------
            $Periodo = new Periodo();
            $PeriodoInscExamen2 = new PeriodoInscExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_2do_per_insc_exam');
            $Periodo->fecha_fin    = $this->request->json('fin_2do_per_insc_exam');
            $Periodo->tipo         = 'IE';
            $Periodo->numero       = 2;
            $Periodo->save();
            $PeriodoInscExamen2->periodo()->associate($Periodo);
            $PeriodoInscExamen2->save();
            
            $Periodo = new Periodo();
            $PeriodoExamen2 = new PeriodoExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_2do_per_exam');
            $Periodo->fecha_fin    = $this->request->json('fin_2do_per_exam');
            $Periodo->tipo         = 'EX';
            $Periodo->numero       = 2;
            $Periodo->save();
            $PeriodoExamen2->periodo()->associate($Periodo);
            $PeriodoExamen2->save();
            $PeriodoExamen2->id = $Periodo->id;
            
            array_push($periodosExamen, $PeriodoExamen2);

            $Periodo = new Periodo();
            $PeriodoInscCurso2 = new PeriodoInscCurso();
            $Periodo->fecha_inicio = $this->request->json('ini_2do_per_insc_lect');
            $Periodo->fecha_fin    = $this->request->json('fin_2do_per_insc_lect');
            $Periodo->tipo         = 'IC';
            $Periodo->numero       = 2;
            $Periodo->save();
            $PeriodoInscCurso2->periodo()->associate($Periodo);
            $PeriodoInscCurso2->save();
            
            $Periodo = new Periodo();
            $PeriodoLectivo2 = new PeriodoLectivo();
            $Periodo->fecha_inicio = $this->request->json('ini_2do_per_lect');
            $Periodo->fecha_fin    = $this->request->json('fin_2do_per_lect');
            $Periodo->tipo         = 'LE';
            $Periodo->numero       = 2;
            $Periodo->save();
            $PeriodoLectivo2->periodo()->associate($Periodo);
            $PeriodoLectivo2->save();
            $PeriodoLectivo2->id = $Periodo->id;

            array_push($periodosLectivos, $PeriodoLectivo2);

            //3---------------------------
            $Periodo = new Periodo();
            $PeriodoInscExamen3 = new PeriodoInscExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_3er_per_insc_exam');
            $Periodo->fecha_fin    = $this->request->json('fin_3er_per_insc_exam');
            $Periodo->tipo         = 'IE';
            $Periodo->numero       = 3;
            $Periodo->save();
            $PeriodoInscExamen3->periodo()->associate($Periodo);
            $PeriodoInscExamen3->save();
            
            $Periodo = new Periodo();
            $PeriodoExamen3 = new PeriodoExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_3er_per_exam');
            $Periodo->fecha_fin    = $this->request->json('fin_3er_per_exam');
            $Periodo->tipo         = 'EX';
            $Periodo->numero       = 3;
            $Periodo->save();
            $PeriodoExamen3->periodo()->associate($Periodo);
            $PeriodoExamen3->save();
            $PeriodoExamen3->id = $Periodo->id;

            array_push($periodosExamen, $PeriodoExamen3);

            //-------------------------------
            $this->InsertarExamenesYEdicionCurso($periodosExamen, $periodosLectivos);

            DB::commit();

            return response()->json(null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al guardar el año lectivo. ' . $e->getMessage()], 500);
        }
    }

    private function obtenerCursosDeSede(Sede $sede){
        $cursos = array();
        foreach ($sede->carreras as $idCarrera => $carrera) {
            foreach ($carrera->cursos as $idCurso => $curso) {
                $cursos[$curso->id] = $curso;
            }
        }
        return $cursos;
    }

    private function InsertarExamenesYEdicionCurso($periodosExamenes, $periodosLectivos){
        // $periodosExamenes es un array de PeriodoExamen
        // $periodosLectivos es un array de PeriodoLectivo

        // se obtienen las Sedes y se recorren una por una
        $sedes = Sede::all();
        foreach ($sedes as $sede) {

            // se obtienen todos los Cursos que se dictan en la Sede y para cada uno se generan EdicionCurso y Examen para cada Periodo
            $cursos = $this->obtenerCursosDeSede($sede);
            foreach ($cursos as $idCurso => $curso) {

                // para cada Curso se generan un Examen para cada Periodo de examenes
                foreach ($periodosExamenes as $perEx) {

                    // se crea y guarda el Examen
                    $examen = new Examen();
                    $examen->fecha = $perEx->fecha_inicio;
                    $examen->periodoExamen()->associate($perEx);
                    $examen->sede()->associate($sede);
                    $examen->curso()->associate($curso);
                    $examen->save();
                }

                // para cada Curso se generan un EdicionCurso para cada Periodo Lectivo
                foreach ($periodosLectivos as $perLec) {
                    // verifico si hay que insertar sergun semestre par o inpar

                    $insertar = false;
                    // si el curso se dicta en ambos semestres, se inserta
                    // si el periodo es par   y el curso se dicta en semestre par,   se inserta
                    // si el periodo es inpar y el curso se dicta en semestre inpar, se inserta
                    if ($curso->seDictaEnSemestreParOInpar() == 0){
                        $insertar = true;
                    }else if ($perLec->periodo->numero == 2 && $curso->seDictaEnSemestreParOInpar() == 2){
                        $insertar = true;
                    }else if ($perLec->periodo->numero == 1 && $curso->seDictaEnSemestreParOInpar() == 1){
                        $insertar = true;
                    }

                    if ($insertar) {
                        // se crea y guarda la EdicionCurso
                        $edicionCurso = new EdicionCurso();
                        $edicionCurso->periodoLectivo()->associate($perLec);
                        $edicionCurso->sede()->associate($sede);
                        $edicionCurso->curso()->associate($curso);
                        $edicionCurso->save();
                    }
                }
            }
        }
    }

    /**
     * @OA\Get(
     *     path="/periodos/actual/{tipo}",
     *     tags={"Periodos"},
     *     @OA\Parameter(
     *         name="tipo",
     *         in="path",
     *         description="Tipo de periodo. Puede ser 'IE', 'EX', 'IC' o 'LE'",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Si se está en un periodo del tipo especificado",
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Si NO se está en un periodo del tipo especificado",
     *     ),
     * )
     */
    public function actual($tipo){
        $tiposPosibles = ['IE', 'EX', 'IC', 'LE'];
        try {
            $hoy = date('Y-m-d');
            $tipo = strtoupper($tipo);
            if ( ! in_array($tipo, $tiposPosibles)){
                throw new \Exception($tipo . " no es un tipo válido");
            }

            $res = Periodo::where('tipo', $tipo)
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin',    '>=', $hoy)
            ->get();

            if (count($res) > 0){
                return response()->json(null, 200);
            }else{
                return response()->json(['message' => 'No se está en un periodo de tipo ' . $tipo], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /*
    {
        "ini_1er_per_insc_exam": "2020-01-01",
        "fin_1er_per_insc_exam": "2020-01-15",
        "ini_1er_per_exam":      "2020-02-01",
        "fin_1er_per_exam":      "2020-02-15",
        "ini_1er_per_insc_lect": "2020-03-01",
        "fin_1er_per_insc_lect": "2020-03-15",
        "ini_1er_per_lect":      "2020-04-01",
        "fin_1er_per_lect":      "2020-04-15",
        "ini_2do_per_insc_exam": "2020-05-01",
        "fin_2do_per_insc_exam": "2020-05-15",
        "ini_2do_per_exam":      "2020-06-01",
        "fin_2do_per_exam":      "2020-06-15",
        "ini_2do_per_insc_lect": "2020-07-01",
        "fin_2do_per_insc_lect": "2020-07-15",
        "ini_2do_per_lect":      "2020-08-01",
        "fin_2do_per_lect":      "2020-08-15",
        "ini_3er_per_insc_exam": "2020-09-01",
        "fin_3er_per_insc_exam": "2020-09-15",
        "ini_3er_per_exam":      "2020-10-01",
        "fin_3er_per_exam":      "2020-10-15"
    }
    */

    // Inscripción a primer período de exámenes
    // ini_1er_per_insc_exam
    // fin_1er_per_insc_exam

    // // Primer período de exámenes
    // ini_1er_per_exam
    // fin_1er_per_exam

    // // Inscripción a primer período lectivo
    // ini_1er_per_insc_lect
    // fin_1er_per_insc_lect

    // // Primer período lectivo
    // ini_1er_per_lect
    // fin_1er_per_lect

    // // Inscripción a segundo período de exámenes
    // ini_2do_per_insc_exam
    // fin_2do_per_insc_exam

    // // Segundo período de exámenes
    // ini_2do_per_exam
    // fin_2do_per_exam

    // // Inscripción a segundo período lectivo
    // ini_2do_per_insc_lect
    // fin_2do_per_insc_lect

    // // Segundo período lectivo
    // ini_2do_per_lect
    // fin_2do_per_lect

    // // Inscripción a tercer período de exámenes
    // ini_3er_per_insc_exam
    // fin_3er_per_insc_exam

    // // Tercer período de exámenes
    // ini_3er_per_exam
    // fin_3er_per_exam
}
