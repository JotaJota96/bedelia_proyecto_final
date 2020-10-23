<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EdicionCurso;
use App\Models\Docente;
use App\Models\Usuario;
use App\Models\Persona;
use App\Models\ClaseDictada;

class EdicionesCursoController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }
    /**
     * @OA\Post(
     *     path="/edicionesCurso/{id}/inscripciones/{ciEstudiante}",
     *     tags={"Ediciones Curso"},
     *     description="Inscribe a un estudiante a una edicion de curso",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la edicion del curso",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="ciEstudiante",
     *         in="path",
     *         description="CI del estudiante a inscribir",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     ),
     * )
     */
    public function asignarEstudiante($id, $idEstudiante){
        try {
            DB::beginTransaction();
            $EdicionCurso = EdicionCurso::where('id', $id)->first();
            // return response()->json($EdicionCurso, 200);
            $Usuario = Usuario::buscar($idEstudiante);
            $Usuario->estudiante;
            // return response()->json($Estudiante, 200);
            // return response()->json($Docente, 200);
            $datosTablaIntermedia = [
                'nota' => 0,
            ];
            $Usuario->estudiante->edicionesCurso()->attach($EdicionCurso, $datosTablaIntermedia);
            $Usuario->save();
            DB::commit();
            return response()->json(null, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
    } 
    
    /**
     * @OA\Put(
     *     path="/edicionesCurso/{id}/docente/{ciDocente}",
     *     tags={"Ediciones Curso"},
     *     description="Asigna un docente a una edicion de curso",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del edicion curso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="ciDocente",
     *         in="path",
     *         description="CI del docente",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="",
     *     ),
     * )
     */
    public function asignarDocente($id, $ciDocente){
        // viene un UsuarioDTO en body
        // asocia a un EdicionCurso (ya existente) con un Docente (ya existente)
        try {
            DB::beginTransaction();
            $EdicionCurso = EdicionCurso::where('id', $id)->first();
            $Usuario = Usuario::buscar($ciDocente);
            //$UsuarioDTO = new persona();
            //$UsuarioDTO->fill($this->request->json(['persona']));
            // return response()->json($EdicionCurso, 200);
            $Docente = $Usuario->docente;
                        
            // return response()->json($Docente, 200);
            $EdicionCurso->docente()->associate($Docente);
            $EdicionCurso->save();
            DB::commit();
            return response()->json(null, 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
    }

    public function CursosDocente($ciDocente) {
        try {
            $Docente=Docente::find($ciDocente);
            $Cursos = $Docente->edicionesCursoActuales();
            return response()->json($Cursos, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }

    public function estudiantes($id) {
        try {
            $EdicionCurso=EdicionCurso::find($id);
            foreach ($EdicionCurso->estudiantes as $estudiante) {
                $estudiante->usuario->persona;
            }
            return response()->json($EdicionCurso->estudiantes, 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 404);
        }
    }
    
    public function claseDictada($id) {
        try {
            DB::beginTransaction();
            $Hoy = date('Y-m-d');
            $EdicionCurso=EdicionCurso::find($id);
            $data = $this->request->json('asistencias');
            $ClaseDictada = new ClaseDictada();
            $ClaseDictada->fecha=$Hoy;
            $ClaseDictada->EdicionCurso()->associate($EdicionCurso);
            $ClaseDictada->save();
            foreach ($data as $asistencia) {
                $datosTablaIntermedia = [
                    'asistencia' => $asistencia['asistencia']
                ];
                $estudiante = Usuario::buscar($asistencia['ciEstudiante']);
                $ClaseDictada->estudiantes()->attach($estudiante, $datosTablaIntermedia);
                // $ClaseDictada->pivot->asistencia=$asistencia['asistencia'];
            }
            $ClaseDictada->save();
            foreach ($ClaseDictada->estudiantes as $asistencia) {
                $asistencia->pivot->asistencia;
            }
            DB::commit();
            return $ClaseDictada;
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 404);
        }
    }
}
