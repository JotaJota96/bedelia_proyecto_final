<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Carrera;
use App\Models\Examen;
use App\Models\Usuario;
use App\Models\PeriodoExamen;
use App\Models\PeriodoInscExamen;

class ExamenController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }



    /**
     * @OA\Post(
     *     path="/examenes/inscripciones/{ciEstudiante}",
     *     tags={"Exámenes"},
     *     description="Inscribe a un estudiante a exámenes",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="ciEstudiante",
     *         in="path",
     *         description="CI del estudiante a inscribir",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="array",
     *             description="ID de los Examenes a los que se inscribirá el estudiante",
     *             @OA\Items(type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     ),
     * )
     */
    
    public function asignarEstudiante($ciEstudiante){
        try {
            DB::beginTransaction();
            $idExamenes = $this->request->json()->all();

            $Usuario = Usuario::buscar($ciEstudiante);
            if ($Usuario == null || $Usuario->estudiante == null) throw new \Exception("No se encontro el estudiante");

            foreach ($idExamenes as $id) {
                $Examen = Examen::where('id', $id)->first();
                if ($Examen == null) throw new \Exception("No se encontro el Examen");
                
                //$Usuario->estudiante;
                $datosTablaIntermedia = [
                    'nota' => 0,
                ];
                $Usuario->estudiante->examenes()->attach($Examen, $datosTablaIntermedia);
                $Usuario->save();
            }
            DB::commit();
            return response()->json($idExamenes, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al registrar la inscripcion.' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/examenes/{id}/docente/{ciDocente}",
     *     tags={"Exámenes"},
     *     description="Asigna un docente a una exámen",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del exámen",
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
        // asocia a un Examen (ya existente) con un Docente (ya existente)
        try {
            DB::beginTransaction();
            $examen = Examen::where('id', $id)->first();
            $Usuario = Usuario::buscar($ciDocente);
            if ($Usuario == null || $Usuario->docente == null){
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }
            //$UsuarioDTO = new persona();
            //$UsuarioDTO->fill($this->request->json(['persona']));
            // return response()->json($examen, 200);
            $Docente = $Usuario->docente;
                        
            // return response()->json($Docente, 200);
            $examen->docente()->associate($Docente);
            $examen->save();
            DB::commit();
            return response()->json(null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/examenes/docente/{ciDocente}",
     *     tags={"Exámenes"},
     *     description="devuelve los Examen que el docente toma en el PeriodoExamen actual",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="ciDocente",
     *         in="path",
     *         description="CI del docente",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ExamenDTO"),
     *         ),
     *     ),
     * )
     */
    public function ExamenessDocente($ciDocente) {
        // devuelve un array de las EdicionCurso que el docente dicta en el PeriodoLectivo actual
        try {
            $Usuario = Usuario::buscar($ciDocente);
            if ($Usuario == null || $Usuario->docente == null){
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }
            $Docente = $Usuario->docente;
            $Cursos = $Docente->examenesActuales();
            return response()->json($Cursos, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/examenes/{ciEstudiante}/{idCarrera}",
     *     tags={"Exámenes"},
     *     description="Devuelve los Examenes a los que el estudiante puede inscribirse en el PeriodoExamen actual",
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
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ExamenDTO"),
     *         ),
     *     ),
     * )
     */
    public function listarParaInscripcion($ciEstudiante, $idCarrera){
        try{
            // obtengo y verifico el Usuario y el Estudiante
            $usu = Usuario::buscar($ciEstudiante);
            if ($usu == null) return response()->json(['message' => 'No se encontró el usuario.'], 404);
            $est = $usu->estudiante;
            if ($est == null) throw new \Exception("El usuario no es estudiante");

            // obtengo la sede
            $idSede = 0;
            foreach ($est->inscripcionesCarrera as $insc) {
                if ($insc->carrera->id == $idCarrera){
                    $idSede = $insc->sede->id;
                }
            }

            // obtengo los cursos y los guardo en un array asociativo (id => curso)
            $carrera = Carrera::find($idCarrera);
            if ($carrera == null) return response()->json(['message' => 'No se encontro la carrera.'], 404);
            $cursos = array();
            foreach ($carrera->cursos as $value) $cursos[$value->id] = $value;

            // obtengo el ID del proximo PeriodoLectivo (semestre)
            $perProx = PeriodoInscExamen::periodoActual();
            if ($perProx == null) throw new \Exception("Aún no se ha definido el próximo período de exámenes");
            $idProxPerEx = $perProx->periodoExamen->id;
            
            // obtengo todos los Examen para el proximo PeriodoExamen y me quedo solo con los de la carrera especificada
            $idExamenes = array();
            $idExamenesPreFiltro = Examen::where('periodo_examen_id', $idProxPerEx)
                ->where('sede_id', $idSede)->get();
            foreach ($idExamenesPreFiltro as $e) {
                foreach ($e->curso->carreras as $c) {
                    $e->habilitado = 1;
                    if ($c->id == $idCarrera){
                        array_push($idExamenes, $e);
                    }
                }
            }
            
            // para cada curso y examen tomado, clasifico segun nota obtenida
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
            
            // Ahora si viene lo chido... Verificar...
            // "curso"  significa que hay que haber llegado a 25/100 = 2.0
            // "examen" significa que hay que haber llegado a 60/100 = 3.0
            // se van recorriendo los EdicionCurso y se marcan con 'habilitado = false' los que correpondan
            foreach ($idExamenes as $ex) {
                $idCurso = $ex->curso->id;
                // error_log("-----------------------------");
                // error_log("Verificando curso $idCurso");

                // para cada Examen a listar, se verifican si el estudiante ya esta inscrito
                if ($ex->estudiantes->where("id", $est->id)->first() != null){
                    $ex->habilitado = -2;
                     // error_log("Ya se ha inscrito en el examen");
                     // limpieza de datos para retornar
                     unset($ex->curso->carreras);
                     unset($ex->curso->previas);
                    continue;
                }

                // para cada Examen a listar, se verifican si el curso ya fuá aprobado
                if (in_array($idCurso, $idCursosExonerados) || in_array($idCurso, $idCursosExamenAprobado)) {
                    $ex->habilitado = 0;
                    // error_log("El curso ha sido aprobado");
                    // limpieza de datos para retornar
                    unset($ex->curso->carreras);
                    unset($ex->curso->previas);
                    continue;
                }

                // para cada Examen a listar, se verifican si el curso se debe recursar
                if (in_array($idCurso, $idCursosARecursar)
                    || !(in_array($idCurso, $idCursosExonerados) || in_array($idCurso, $idCursosAExamen)) ){
                    $ex->habilitado = -1;
                    // error_log("El curso ha sido aprobado");
                    // limpieza de datos para retornar
                    unset($ex->curso->carreras);
                    unset($ex->curso->previas);
                    continue;
                }

                // si llego hasta aca es porque se cumple con todas las previas
                // limpieza de datos para retornar
                unset($ex->curso->carreras);
                unset($ex->curso->previas);
            }

            return response()->json($idExamenes, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los cursos.' . $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/examenes/{id}/notas",
     *     tags={"Exámenes"},
     *     description="Obtiene las notas obtenidas por los estudiantes",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del examen",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/ActaDTO"),
     *     ),
     * )
     */
    public function ObtenerNotas($id){
        // devuelve las notas obtenidas por los estudiantes del Examen
        try {
            $Examen = Examen::find($id);
            if ($Examen == null){
                throw new \Exception("Examen no encontrado");
            }
            return response()->json($Examen->obtenerActa(), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener notas.' . $e->getMessage()], 500);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/examenes/{id}/notas",
     *     tags={"Exámenes"},
     *     description="Registra las notas obtenidas por los estudiantes",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del examen",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/ActaDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/ActaDTO"),
     *     ),
     * )
     */
    public function IngresarNotas($id){
        // guarda las notas obtenidas por los estudiantes del Examen
        try {
            DB::beginTransaction();
            $Examen = Examen::where('id', $id)->first();
            if ($Examen == null){
                throw new \Exception("Examen no encontrado");
            }
            if ($Examen->acta_confirmada == true){
                throw new \Exception("No se pueden actualizar las notas, el acta ya fue confirmada");
            }
            $notas = $this->request->json('notas');
            foreach ($notas as $nota) {
                $usu = Usuario::buscar($nota['ciEstudiante']);
                $Examen->estudiantes->where('id',$usu->id)->first()->pivot->nota=$nota['nota'];
                $Examen->estudiantes->where('id',$usu->id)->first()->pivot->save();
            }
            DB::commit();
            return response()->json(null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al ingresar las notas.' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/examenes/{id}/notas",
     *     tags={"Exámenes"},
     *     description="Marca como confirmada el acta del Examen",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del examen",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/ActaDTO"),
     *     ),
     * )
     */
    public function ConfirmarActa($id){
        // actualiza 'acta_confirmada' = true para el Examen especificado
        try {
            DB::beginTransaction();
            $Examen = Examen::where('id', $id)->first();
            $Examen->acta_confirmada = true;
            $Examen->save();
            DB::commit();
            return response()->json(null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al confirmar el acta.' . $e->getMessage()], 500);
        }
    }

}
