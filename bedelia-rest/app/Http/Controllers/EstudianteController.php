<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Examen;
use App\Models\Carrera;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Estudiante;

class EstudianteController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

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
}
