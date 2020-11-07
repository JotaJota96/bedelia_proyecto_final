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
     *     path="/edicionesCurso/inscripciones/{ciEstudiante}",
     *     tags={"Ediciones Curso"},
     *     description="Inscribe a un estudiante a ediciones de curso",
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
     *             description="ID de los EdicionCurso a los que se inscribirá el estudiante",
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
            $idEdicionesCurso = $this->request->json()->all();

            $Usuario = Usuario::buscar($ciEstudiante);
            if ($Usuario == null || $Usuario->estudiante == null) throw new \Exception("No se encontro el estudiante");

            foreach ($idEdicionesCurso as $id) {
                $EdicionCurso = EdicionCurso::where('id', $id)->first();
                if ($EdicionCurso == null) throw new \Exception("No se encontro el EdicionCurso");

                // return response()->json($EdicionCurso, 200);
                //$Usuario->estudiante;
                // return response()->json($Estudiante, 200);
                // return response()->json($Docente, 200);
                $datosTablaIntermedia = [
                    'nota' => 0,
                ];
                $Usuario->estudiante->edicionesCurso()->attach($EdicionCurso, $datosTablaIntermedia);
                $Usuario->save();
            }
            DB::commit();
            return response()->json($idEdicionesCurso, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al registrar la inscripcion.' . $e->getMessage()], 500);
        }
    } 
    
    /**
     * @OA\Put(
     *     path="/edicionesCurso/{id}/docente/{ciDocente}",
     *     tags={"Ediciones Curso"},
     *     description="Asigna un docente a una edicion de curso",
     *     security={{"api_key": {}}},
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
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/edicionesCurso/docente/{ciDocente}",
     *     tags={"Ediciones Curso"},
     *     description="devuelve los EdicionCurso que el docente dicta en el PeriodoLectivo actual",
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
     *             @OA\Items(ref="#/components/schemas/EdicionCursoDTO"),
     *         ),
     *     ),
     * )
     */
    public function CursosDocente($ciDocente) {
        // devuelve un array de las EdicionCurso que el docente dicta en el PeriodoLectivo actual
        try {
            $Usuario = Usuario::buscar($ciDocente);
            if ($Usuario == null){
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }
            $Docente = $Usuario->docente;
            $Cursos = $Docente->edicionesCursoActuales();
            return response()->json($Cursos, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/edicionesCurso/{id}/estudiantes",
     *     tags={"Ediciones Curso"},
     *     description="Devuelve los estudiantes inscritos al EdicionCurso",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del edicion curso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/ClaseDictadaDTO"),
     *     ),
     * )
     */
    public function estudiantes($id) {
        // {id} ID del EdicionCurso
        // devuelve un array con los estudiantes inscritos al EdicionCurso especificado
        try {
            // array asociativo que al ser retornado se transforma en un JSON
            $ret = [
                "lista" => [], // lista de datos de alumnos
            ]; 

            $EdicionCurso=EdicionCurso::find($id);
            if ($EdicionCurso == null){
                return response()->json(['message' => 'EdicionCurso no encontrado'], 404);
            }
            

            $ret['curso_id']         = $EdicionCurso->curso->id;
            $ret['edicion_curso_id'] = $EdicionCurso->id;

            foreach ($EdicionCurso->estudiantes as $estudiante) {
                //$estudiante->usuario->persona;
                // agrego al array 'lista' (dentro del array $ret), los datos de cada estudiante inscripto
                array_push($ret['lista'], array(
                    'ciEstudiante'         => $estudiante->usuario->persona->cedula,
                    'nombre'               => $estudiante->usuario->persona->nombre,
                    'apellido'             => $estudiante->usuario->persona->apellido,
                    "total_asistencias"    => $EdicionCurso->contarAsistidas($estudiante->id, 1.0),
                    "total_llegadas_tarde" => $EdicionCurso->contarAsistidas($estudiante->id, 0.5),
                    "total_inasistencias"  => $EdicionCurso->contarAsistidas($estudiante->id, 0.0),
                ));
            }
            return response()->json($ret, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/edicionesCurso/{id}/clasesDictada",
     *     tags={"Ediciones Curso"},
     *     description="Registra una nueva ClaseDictada y la asistencias de los estudiantes",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del edicion curso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/ClaseDictadaDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/ClaseDictadaDTO"),
     *     ),
     * )
     */
    public function Agregar($id) {
        try {
            DB::beginTransaction();
            $Hoy = date('Y-m-d');
            $EdicionCurso=EdicionCurso::find($id);
            if ($EdicionCurso == null){
                return response()->json(['message' => 'EdicionCurso no encontrado'], 404);
            }
            $data = $this->request->json('lista');
            $ClaseDictada = new ClaseDictada();
            $ClaseDictada->fecha=$Hoy;
            $ClaseDictada->EdicionCurso()->associate($EdicionCurso);
            $ClaseDictada->save();
            foreach ($data as &$asistencia) {
                $datosTablaIntermedia = [
                    'asistencia' => $asistencia['asistencia']
                ];
                $usu = Usuario::buscar($asistencia['ciEstudiante']);
                if ($usu == null){
                    throw new \Exception('Usuario ' . $asistencia['ciEstudiante'] . ' no encontrado');
                }
                $estudiante = $usu->estudiante;
                $ClaseDictada->estudiantes()->attach($estudiante, $datosTablaIntermedia);
                // $ClaseDictada->pivot->asistencia=$asistencia['asistencia'];

                // carga datos para devolver
                $asistencia['nombre']               = $estudiante->usuario->persona->nombre;
                $asistencia['apellido']             = $estudiante->usuario->persona->apellido;
                $asistencia["total_asistencias"]    = $estudiante->contarAsistidas($estudiante->id, 1.0);
                $asistencia["total_llegadas_tarde"] = $estudiante->contarAsistidas($estudiante->id, 0.5);
                $asistencia["total_inasistencias"]  = $estudiante->contarAsistidas($estudiante->id, 0.0);
            }
            $ClaseDictada->save();
            DB::commit();

            // foreach ($ClaseDictada->estudiantes as $asistencia) {
            //     $asistencia->pivot->asistencia;
            // }

            // datos para devolver
            $ret = [
                'id'                => $ClaseDictada->id,
                'fecha'             => $ClaseDictada->fecha,
                'curso_id'          => $ClaseDictada->edicionCurso->curso->id,
                'edicion_curso_id'  => $ClaseDictada->edicionCurso->id,
                'lista'             => $data,
            ];
            return response()->json($ret, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/edicionesCurso/{ciEstudiante}/{idCarrera}",
     *     tags={"Ediciones Curso"},
     *     description="Los EdicionCurso a los que el estudiante puede inscribirse en el PeriodoLectivo actual",
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
     *             @OA\Items(ref="#/components/schemas/EdicionCursoDTO"),
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
            $perProx = PeriodoInscCurso::periodoActual();
            if ($perProx == null) throw new \Exception("Aún no se ha definido el próximo período lectivo");
            $idProxPerLec = $perProx->periodoLectivo->id;
            
            // obtengo todos los EdicionCurso para el proximo PeriodoLectivo (semestre) y me quedo solo con los de la carrera especificada
            $edicionesCurso = array();
            $edicionesCursoPreFiltro = EdicionCurso::where('periodo_lectivo_id', $idProxPerLec)
                ->where('sede_id', $idSede)->get();
            foreach ($edicionesCursoPreFiltro as $ec) {
                foreach ($ec->curso->carreras as $c) {
                    $ec->habilitado = 1;
                    if ($c->id == $idCarrera){
                        array_push($edicionesCurso, $ec);
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

            // Ahora si viene lo chido... Verificar las previas...
            // "curso"  significa que hay que haber llegado a 25/100 = 2.0
            // "examen" significa que hay que haber llegado a 60/100 = 3.0
            // se van recorriendo los EdicionCurso y se marcan con 'habilitado = false' los que correpondan
            foreach ($edicionesCurso as $ec) {
                $idCurso = $ec->curso->id;
                // error_log("-----------------------------");
                // error_log("Verificando curso $idCurso");

                // para cada EdicionCurso a listar, se verifican si el curso ya fuá aprobado
                if (in_array($idCurso, $idCursosExonerados) || in_array($idCurso, $idCursosExamenAprobado)){
                    $ec->habilitado = 0;
                     // error_log("El curso ha sido aprobado");
                     // limpieza de datos para retornar
                     unset($ec->curso->carreras);
                     unset($ec->curso->previas);
                    continue;
                }
                // error_log("El curso NO ha sido aprobado");

                // para cada EdicionCurso a listar, se verifican las previas
                $previas = $ec->curso->previas;
                // error_log("previas:");
                foreach ($previas as $p) {
                    // error_log("    requiere $p->tipo de " . $p->previa->id);

                    // si se encuentra que no se aprobó el curso para un curso que lo requería
                    if (strcmp($p->tipo, "curso") == 0 && 
                        ! ( in_array($p->previa->id, $idCursosExonerados) || 
                        in_array($p->previa->id, $idCursosAExamen) || 
                        in_array($p->previa->id, $idCursosExamenAprobado))) {
    
                        // error_log("      no se cumple con la previa");
                        $ec->habilitado = -1;
                        break;
                    }

                    // si se encuentra que no se exoneró el curso o no se aprobó el examen para un curso que lo requería
                    if (strcmp($p->tipo, "examen") == 0 && 
                        ! (in_array($p->previa->id, $idCursosExonerados) || 
                        in_array($p->previa->id, $idCursosExamenAprobado))){

                        // error_log("      no se cumple con la previa");
                        $ec->habilitado = -1;
                        break;
                    }
                }

                // si llego hasta aca es porque se cumple con todas las previas
                // limpieza de datos para retornar
                unset($ec->curso->carreras);
                unset($ec->curso->previas);
            }

            return response()->json($edicionesCurso, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener los cursos.' . $e->getMessage()], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/edicionesCurso/{id}/notas",
     *     tags={"Ediciones Curso"},
     *     description="Obtiene las notas obtenidas por los estudiantes",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del edicion curso",
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
        // devuelve las notas obtenidas por los estudiantes del EdicionCurso
        try {
            $EdicionCurso = EdicionCurso::where('id', $id)->first();
            if ($EdicionCurso == null){
                throw new \Exception("EdicionCurso no encontrado");
            }
            return response()->json($EdicionCurso->obtenerActa(), 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener notas.' . $e->getMessage()], 500);
        }
    }
    
    /**
     * @OA\Post(
     *     path="/edicionesCurso/{id}/notas",
     *     tags={"Ediciones Curso"},
     *     description="Registra las notas obtenidas por los estudiantes",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del edicion curso",
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
        // guarda las notas obtenidas por los estudiantes del EdicionCurso
        try {
            DB::beginTransaction();
            $EdicionCurso = EdicionCurso::where('id', $id)->first();
            if ($EdicionCurso == null){
                throw new \Exception("EdicionCurso no encontrado");
            }
            if ($EdicionCurso->acta_confirmada == true){
                throw new \Exception("No se pueden actualizar las notas, el acta ya fue confirmada");
            }
            $notas = $this->request->json('notas');
            foreach ($notas as $nota) {
                $usu = Usuario::buscar($nota['ciEstudiante']);
                $EdicionCurso->estudiantes->where('id',$usu->id)->first()->pivot->nota=$nota['nota'];
                $EdicionCurso->estudiantes->where('id',$usu->id)->first()->pivot->save();
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
     *     path="/edicionesCurso/{id}/notas",
     *     tags={"Ediciones Curso"},
     *     description="Marca como confirmada el acta del EdicionCurso",
     *     security={{"api_key": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del edicion curso",
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
        // actualiza 'acta_confirmada' = true para el EdicionCurso especificado
        try {
            DB::beginTransaction();
            $EdicionCurso = EdicionCurso::where('id', $id)->first();
            $EdicionCurso->acta_confirmada = true;
            $EdicionCurso->save();
            DB::commit();
            return response()->json(null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al confirmar el acta.' . $e->getMessage()], 500);
        }
    }

    

}
