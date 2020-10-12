<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodo;
use App\Models\PeriodoLectivo;
use App\Models\PeriodoInscExamen;
use App\Models\PeriodoExamen;
use App\Models\PeriodoInscCurso;
use Illuminate\Support\Facades\DB;

class PeriodoLectivoController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }
    
    public function obtenerLista(){
        $PeriodoInscExamen= PeriodoInscExamen::orderby('id', 'desc')->first();
        $PeriodoExamen= PeriodoExamen::orderby('id', 'desc')->first();
        $PeriodoInscCurso= PeriodoInscCurso::orderby('id', 'desc')->first();
        $PeriodoLectivo= PeriodoLectivo::orderby('id', 'desc')->first();
        $PeriodoInscExamen2= PeriodoInscExamen::orderby('id', 'desc')->first();
        $PeriodoExamen2= PeriodoExamen::orderby('id', 'desc')->first();
        $PeriodoInscCurso2= PeriodoInscCurso::orderby('id', 'desc')->first();
        $PeriodoLectivo2= PeriodoLectivo::orderby('id', 'desc')->first();
        $PeriodoInscExamen3= PeriodoInscExamen::orderby('id', 'desc')->first();
        $PeriodoExamen3= PeriodoExamen::orderby('id', 'desc')->first();
        $items = array(
            "ini_1er_per_insc_exam"=> $PeriodoInscExamen->Periodo->fecha_inicio,
            "fin_1er_per_insc_exam"=> $PeriodoInscExamen->Periodo->fecha_fin,
            "ini_1er_per_exam"=> $PeriodoExamen->Periodo->fecha_inicio,
            "fin_1er_per_exam"=> $PeriodoExamen->Periodo->fecha_fin,
            "ini_1er_per_insc_lect"=> $PeriodoInscCurso->Periodo->fecha_inicio,
            "fin_1er_per_insc_lect"=> $PeriodoInscCurso->Periodo->fecha_fin,
            "ini_1er_per_lect"=> $PeriodoLectivo->Periodo->fecha_inicio,
            "fin_1er_per_lect"=> $PeriodoLectivo->Periodo->fecha_fin,

            "ini_2do_per_insc_exam"=> $PeriodoInscExamen2->Periodo->fecha_inicio,
            "fin_2do_per_insc_exam"=> $PeriodoInscExamen2->Periodo->fecha_fin,
            "ini_2do_per_exam"=> $PeriodoExamen2->Periodo->fecha_inicio,
            "fin_2do_per_exam"=> $PeriodoExamen2->Periodo->fecha_fin,
            "ini_2do_per_insc_lect"=> $PeriodoInscCurso2->Periodo->fecha_inicio,
            "fin_2do_per_insc_lect"=> $PeriodoInscCurso2->Periodo->fecha_fin,
            "ini_2do_per_lect"=> $PeriodoLectivo2->Periodo->fecha_inicio,
            "fin_2do_per_lect"=> $PeriodoLectivo2->Periodo->fecha_fin,
            
            "ini_3er_per_insc_exam"=> $PeriodoInscExamen3->Periodo->fecha_inicio,
            "fin_3er_per_insc_exam"=> $PeriodoInscExamen3->Periodo->fecha_fin,
            "ini_3er_per_exam"=> $PeriodoExamen3->Periodo->fecha_inicio,
            "fin_3er_per_exam"=> $PeriodoExamen3->Periodo->fecha_fin
        );
        // $versiones = array("productos" => array($items));
        json_encode($items);     

        return response()->json($items, 200);
    }
    public function agregar(){
        try {
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

    // {
    //     "ini_1er_per_insc_exam": "2021-01-24",
    //     "fin_1er_per_insc_exam": "2021-01-24",
    //     "ini_1er_per_exam": "2021-01-24",
    //     "fin_1er_per_exam": "2021-01-24",
    //     "ini_1er_per_insc_lect": "2021-01-24",
    //     "fin_1er_per_insc_lect": "2021-01-24",
    //     "ini_1er_per_lect": "2021-01-24",
    //     "fin_1er_per_lect": "2021-01-24",
    //     "ini_2do_per_insc_exam": "2021-01-24",
    //     "fin_2do_per_insc_exam": "2021-01-24",
    //     "ini_2do_per_exam": "2021-01-24",
    //     "fin_2do_per_exam": "2021-01-24",
    //     "ini_2do_per_insc_lect": "2021-01-24",
    //     "fin_2do_per_insc_lect": "2021-01-24",
    //     "ini_2do_per_lect": "2021-01-24",
    //     "fin_2do_per_lect": "2021-01-24",
    //     "ini_3er_per_insc_exam": "2021-01-24",
    //     "fin_3er_per_insc_exam": "2021-01-24",
    //     "ini_3er_per_exam": "2021-01-24",
    //     "fin_3er_per_exam": "2021-01-24"
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
