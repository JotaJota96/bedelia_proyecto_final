<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodo;
use App\Models\PeriodoLectivo;
use App\Models\PeriodoInscExamen;
use App\Models\PeriodoExamen;
use App\Models\PeriodoInscCurso;
use App\Models\Sede;
use App\Models\examen;
use App\Models\EdicionCurso;
use Illuminate\Support\Facades\DB;

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
     *         description="",
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
                "ini_1er_per_insc_exam"=> $PeriodoInscExamen1->fecha_inicio,
                "fin_1er_per_insc_exam"=> $PeriodoInscExamen1->fecha_fin,
                "ini_1er_per_exam"=> $PeriodoExamen1->fecha_inicio,
                "fin_1er_per_exam"=> $PeriodoExamen1->fecha_fin,
                "ini_1er_per_insc_lect"=> $PeriodoInscCurso1->fecha_inicio,
                "fin_1er_per_insc_lect"=> $PeriodoInscCurso1->fecha_fin,
                "ini_1er_per_lect"=> $PeriodoLectivo1->fecha_inicio,
                "fin_1er_per_lect"=> $PeriodoLectivo1->fecha_fin,

                "ini_2do_per_insc_exam"=> $PeriodoInscExamen2->fecha_inicio,
                "fin_2do_per_insc_exam"=> $PeriodoInscExamen2->fecha_fin,
                "ini_2do_per_exam"=> $PeriodoExamen2->fecha_inicio,
                "fin_2do_per_exam"=> $PeriodoExamen2->fecha_fin,
                "ini_2do_per_insc_lect"=> $PeriodoInscCurso2->fecha_inicio,
                "fin_2do_per_insc_lect"=> $PeriodoInscCurso2->fecha_fin,
                "ini_2do_per_lect"=> $PeriodoLectivo2->fecha_inicio,
                "fin_2do_per_lect"=> $PeriodoLectivo2->fecha_fin,
                
                "ini_3er_per_insc_exam"=> $PeriodoInscExamen3->fecha_inicio,
                "fin_3er_per_insc_exam"=> $PeriodoInscExamen3->fecha_fin,
                "ini_3er_per_exam"=> $PeriodoExamen3->fecha_inicio,
                "fin_3er_per_exam"=> $PeriodoExamen3->fecha_fin
            );
            // $versiones = array("productos" => array($items));
            json_encode($items);     
            
            return response()->json($items, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }
    public function agregar(){
        try {
            $Sedes = Sede::all();

            $Periodo = new Periodo();
            $PeriodoInscExamen = new PeriodoInscExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_1er_per_insc_exam');
            $Periodo->fecha_fin = $this->request->json('fin_1er_per_insc_exam');
            $Periodo->tipo = 'IE';
            $PeriodoInscExamen->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoInscExamen->id = $Periodo->id;
            $PeriodoInscExamen->save();
            
            $Periodo = new Periodo();
            $PeriodoExamen = new PeriodoExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_1er_per_exam');
            $Periodo->fecha_fin = $this->request->json('fin_1er_per_exam');
            $Periodo->tipo = 'EX';
            $PeriodoExamen->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoExamen->id = $Periodo->id;
            $PeriodoExamen->save();
            
            $Periodo = new Periodo();
            $PeriodoInscCurso = new PeriodoInscCurso();
            $Periodo->fecha_inicio = $this->request->json('ini_1er_per_insc_lect');
            $Periodo->fecha_fin = $this->request->json('fin_1er_per_insc_lect');
            $Periodo->tipo = 'IC';
            $PeriodoInscCurso->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoInscCurso->id = $Periodo->id;
            $PeriodoInscCurso->save();
            
            $Periodo = new Periodo();
            $PeriodoLectivo = new PeriodoLectivo();
            $Periodo->fecha_inicio = $this->request->json('ini_1er_per_lect');
            $Periodo->fecha_fin = $this->request->json('fin_1er_per_lect');
            $Periodo->tipo = 'LE';
            $PeriodoLectivo->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoLectivo->id = $Periodo->id;
            $PeriodoLectivo->save();
            InsertarLectivo($PeriodoExamen, $PeriodoLectivo, $Sedes);

            //2-----------------------------------------------------
            $Periodo = new Periodo();
            $PeriodoInscExamen2 = new PeriodoInscExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_2do_per_insc_exam');
            $Periodo->fecha_fin = $this->request->json('fin_2do_per_insc_exam');
            $Periodo->tipo = 'IE';
            $PeriodoInscExamen2->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoInscExamen2->id = $Periodo->id;
            $PeriodoInscExamen2->save();
            
            $Periodo = new Periodo();
            $PeriodoExamen2 = new PeriodoExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_2do_per_exam');
            $Periodo->fecha_fin = $this->request->json('fin_2do_per_exam');
            $Periodo->tipo = 'EX';
            $PeriodoExamen2->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoExamen2->id = $Periodo->id;
            $PeriodoExamen2->save();
            
            $Periodo = new Periodo();
            $PeriodoInscCurso2 = new PeriodoInscCurso();
            $Periodo->fecha_inicio = $this->request->json('ini_2do_per_insc_lect');
            $Periodo->fecha_fin = $this->request->json('fin_2do_per_insc_lect');
            $Periodo->tipo = 'IC';
            $PeriodoInscCurso2->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoInscCurso2->id = $Periodo->id;
            $PeriodoInscCurso2->save();

            $Periodo = new Periodo();
            $PeriodoLectivo2 = new PeriodoLectivo();
            $Periodo->fecha_inicio = $this->request->json('ini_2do_per_lect');
            $Periodo->fecha_fin = $this->request->json('fin_2do_per_lect');
            $Periodo->tipo = 'LE';
            $PeriodoLectivo2->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoLectivo2->id = $Periodo->id;
            $PeriodoLectivo2->save();
            InsertarLectivo($PeriodoExamen2, $PeriodoLectivo2, $Sedes);
            
            //3------------------------------------------------------
            $Periodo = new Periodo();
            $PeriodoInscExamen3 = new PeriodoInscExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_3er_per_insc_exam');
            $Periodo->fecha_fin = $this->request->json('fin_3er_per_insc_exam');
            $Periodo->tipo = 'IE';
            $PeriodoInscExamen3->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoInscExamen3->id = $Periodo->id;
            $PeriodoInscExamen3->save();

            $Periodo = new Periodo();
            $PeriodoExamen3 = new PeriodoExamen();
            $Periodo->fecha_inicio = $this->request->json('ini_3er_per_exam');
            $Periodo->fecha_fin = $this->request->json('fin_3er_per_exam');
            $Periodo->tipo = 'EX';
            $PeriodoExamen3->Periodo()->associate($Periodo);
            $Periodo->save();
            $PeriodoExamen3->id = $Periodo->id;
            $PeriodoExamen3->save();
            return response()->json(200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    private function InsertarLectivo($PeriodoExamen, $PeriodoLectivo, $Sedes){
        foreach ($Sedes as $id => $Sede) {
            $Cursos = [];
            foreach ($Sede->carreras as $id => $carrera) {
                foreach ($carrera->cursos as $id => $curso) {
                    if ($Cursos::find($curso->Id)==null) {
                        array_push($Cursos,$Curso);
                    }
                }
            }
            foreach ($Cursos as $id => $Curso) {
                try {
                    $examen = new examen();
                    $examen->fecha = $PeriodoExamen->fecha_inicio;
                    $examen->periodoLectivo()->associate($PeriodoExamen);
                    $examen->sede()->associate($Sede);
                    $examen->curso()->associate($Curso);
                    $examen->save();
                    
                    $EdicionCurso = new EdicionCurso();
                    $EdicionCurso->periodoLectivo()->associate($PeriodoExamen);
                    $EdicionCurso->sede()->associate($Sede);
                    $EdicionCurso->curso()->associate($Curso);
                    $EdicionCurso->save();
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
    }

    // {
    //     "ini_1er_per_insc_exam": "2020-01-24",
    //     "fin_1er_per_insc_exam": "2020-01-24",
    //     "ini_1er_per_exam": "2020-01-24",
    //     "fin_1er_per_exam": "2020-01-24",
    //     "ini_1er_per_insc_lect": "2020-01-24",
    //     "fin_1er_per_insc_lect": "2020-01-24",
    //     "ini_1er_per_lect": "2020-01-24",
    //     "fin_1er_per_lect": "2020-01-24",
    //     "ini_2do_per_insc_exam": "2020-01-24",
    //     "fin_2do_per_insc_exam": "2020-01-24",
    //     "ini_2do_per_exam": "2020-01-24",
    //     "fin_2do_per_exam": "2020-01-24",
    //     "ini_2do_per_insc_lect": "2020-01-24",
    //     "fin_2do_per_insc_lect": "2020-01-24",
    //     "ini_2do_per_lect": "2020-01-24",
    //     "fin_2do_per_lect": "2020-01-24",
    //     "ini_3er_per_insc_exam": "2020-01-24",
    //     "fin_3er_per_insc_exam": "2020-01-24",
    //     "ini_3er_per_exam": "2020-01-24",
    //     "fin_3er_per_exam": "2020-01-24"
    // }

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
