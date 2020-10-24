<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EdicionCurso;
use App\Models\Docente;
use App\Models\Usuario;
use App\Models\Estudiante;
use App\Models\Persona;
use App\Models\ClaseDictada;
use App\Models\Carrera;
use App\Models\PeriodoInscCurso;



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

    public function listarParaInscripcion($ciEstudiante, $idCarrera){
        try{
            $usu = Usuario::buscar($ciEstudiante);
            if ($usu == null) return response()->json(['message' => 'No se encontró el usuario.'], 404);
            $est = $usu->estudiante;
            if ($est == null) throw new \Exception("El usuario no es estudiante");

            $idSede = 0;
            foreach ($est->inscripcionesCarrera as $insc) {
                if ($insc->carrera->id == $idCarrera){
                    $idSede = $insc->sede->id;
                }
            }

            $carrera = Carrera::find($idCarrera);
            if ($carrera == null) return response()->json(['message' => 'No se encontro la carrera.'], 404);
            $cursos = array();
            foreach ($carrera->cursos as $value) $cursos[$value->id] = $value;

            $edicionesCurso = array();
            $edicionesCursoPreFiltro = EdicionCurso::where('periodo_lectivo_id', PeriodoInscCurso::periodoProximo()->periodoLectivo->id)
                ->where('sede_id', $idSede)->get();
            foreach ($edicionesCursoPreFiltro as $ec) {
                foreach ($ec->curso->carreras as $c) {
                    $ec->habilitado = true;
                    if ($c->id == $idCarrera){
                        array_push($edicionesCurso, $ec);
                    }
                }
            }
            
            $idCursosExonerados       = array(); // ID de los cursos que se exoneraron
            $idCursosAExamen          = array(); // ID de los cursos que se debe dar examen
            $idCursosARecursar        = array(); // ID de los cursos que se debe recursar
            $idCursosExamenAprobado   = array(); // ID de los cursos de los que se dio examen y se aprobo
            $idCursosExamenNoAprobado = array(); // ID de los cursos de los que se dio examen y NO se aprobo

            $cursosTomados = $est->NotasCarrera($idCarrera);
            foreach ($cursosTomados as $value) {
                $nota    = $value['nota'];
                $idCurso = $value['curso_id'];
                if ($nota >= 3){
                    array_push($idCursosExonerados, $idCurso);
                }elseif ($nota >= 2){
                    array_push($idCursosAExamen, $idCurso);
                }else{
                    array_push($idCursosARecursar, $idCurso);
                }
            }
            $examenesDados = $est->NotasExamenes($idCarrera);
            foreach ($examenesDados as $value) {
                $nota    = $value['nota'];
                $idCurso = $value['curso_id'];
                if ($nota >= 3){
                    array_push($idCursosExamenAprobado, $idCurso);
                }else{
                    array_push($idCursosExamenNoAprobado, $idCurso);
                }
            }

            // Ahora si viene lo chido... Verificar las previas...
            // "curso"  significa que hay que haber llegado a 25/100 = 2.0
            // "examen" significa que hay que haber llegado a 60/100 = 3.0
            // se van recorriendo los EdicionCurso y se marcan con 'habilitado = false' los que correpondan
            foreach ($edicionesCurso as $ec) {
                $idCurso = $ec->curso->id;
                error_log("-----------------------------");
                error_log("Verificando curso $idCurso");

                // si el curso ya está aprobado
                if (in_array($idCurso, $idCursosExonerados) || in_array($idCurso, $idCursosExamenAprobado)){
                    $ec->habilitado = false;
                     error_log("El curso ha sido aprobado");
                     // limpieza de datos para retornar
                     unset($ec->curso->carreras);
                     unset($ec->curso->previas);
                    continue;
                }
                error_log("El curso NO ha sido aprobado");

                // si se cumple con las previas
                $previas = $ec->curso->previas;

                error_log("previas:");
                foreach ($previas as $p) {
                    error_log("    requiere $p->tipo de " . $p->previa->id);

                    if (strcmp($p->tipo, "curso") == 0 && 
                        ! ( in_array($p->previa->id, $idCursosExonerados) || 
                        in_array($p->previa->id, $idCursosAExamen) || 
                        in_array($p->previa->id, $idCursosExamenAprobado))) {
    
                        error_log("      no se cumple con la previa");

                        $ec->habilitado = false;
                        break;
                    }

                    if (strcmp($p->tipo, "examen") == 0 && 
                        ! (in_array($p->previa->id, $idCursosExonerados) || 
                        in_array($p->previa->id, $idCursosExamenAprobado))){

                        error_log("      no se cumple con la previa");

                        $ec->habilitado = false;
                        break;
                    }
                }

                // limpieza de datos para retornar
                unset($ec->curso->carreras);
                unset($ec->curso->previas);
            }

            return response()->json($edicionesCurso, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al obtener los cursos.' . $e->getMessage()], 500);
        }
    }
}
