<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\Sede;
use App\Models\carrera;
use App\Models\persona;
use App\Models\Direccion;
use App\Models\Postulacion;
use App\Models\Periodo;
use App\Models\PeriodoInscCurso;
use App\Models\PeriodoLectivo;
use App\Models\PeriodoInscExamen;
use App\Models\PeriodoExamen;

class SedesController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    /**
     * @OA\Get(
     *     path="/sedes/{id}",
     *     tags={"Sedes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sede",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos de la sede",
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     * )
     */
    public function obtenerUno(int $id){
        $sede = Sede::find($id);

        if ($sede == null){
            return response()->json(null, 404);
        }
        $sede->direccion;
        return response()->json($sede, 200);
    }

    /**
     * @OA\Get(
     *     path="/sedes/",
     *     tags={"Sedes"},
     *     @OA\Response(
     *         response=200,
     *         description="Todas las sedes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SedeDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerTodos(){
        $sedes = Sede::all();

        foreach ($sedes as $Id => $value) {
            $value->direccion;
        }
        return response()->json($sedes, 200);
    }

    /**
     * @OA\Post(
     *     path="/sedes",
     *     tags={"Sedes"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos de la sede creada",
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     * )
     */
    public function agregar(){
        $sede = new Sede();
        $sede->fill($this->request->json()->all());

        $dir = new Direccion();
        $dir->fill($this->request->json('direccion'));

        $sede->direccion()->associate($dir);

        try {
            DB::beginTransaction();

            // inserta la direccion y se la asocia a la persona
            $dir->save();
            $sede->direccion()->associate($dir);
            $sede->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            //return response()->json(['message' => $e->getMessage()], 500);
            // devuelve un estado HTTP 500 y un mensaje simple del error
            return response()->json(['message' => 'Error al guardar los datos'], 500);
        }

        return response()->json($sede, 200);
    }


    /**
     * @OA\Get(
     *     path="/sedes/{id}/postulantes",
     *     tags={"Sedes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sede",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostulanteDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerListaPostulantesDeSede(int $id){
        error_log($id);
        $Sede = Sede::where('id', $id)->first();
        error_log($Sede);
        if ($Sede != null) {
            $postulaciones = $Sede->postulaciones;
            foreach ($postulaciones as $id => $postulacion) {
                $postulacion->persona->direccion;
                $postulacion->carrera;
                $postulacion->sede;
            }
            return response()->json($postulaciones, 200);
        }
        return response()->json(['message' => 'Error al buscar las postulaciones. '], 404);
        // devuelve la lista de postulantes asociados a una sede especifica
        // para cada postulante recordar devolver la persona asociada y su direccion. Tambien la sede, la carrera

    }


    /**
     * @OA\Get(
     *     path="/sedes/{id}/edicionesCurso",
     *     tags={"Sedes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sede",
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
    public function obtenerEdicionesCurso($id){
        $retEdicionesCursos = array();
        try {
            $Sede = Sede::where('id', $id)->first();
            
            // ---- guarda en $retEdicionesCursos los EdicionCurso que se dicten en el proximo o actual PeriodoLectivo
            $idPeriodo = 0;
            $pla  = PeriodoLectivo::periodoActual();
            $pica = PeriodoLectivo::periodoProximo();
            if ($$pla == null && $pica == null){
                throw new \Exception("No se está en un periodo lectivo ni de inscripcion");
            }
            if ($pla != null) {
                $idPeriodo = $pla->id;
            } else if ($pica != null) {
                $idPeriodo = $pica->id;
            }
            foreach ($Sede->edicionesCurso as $id => $edicionCurso) {
                if ($edicionCurso->periodoLectivo->id == $idPeriodo) {
                    if ($edicionCurso->docente != null) {
                        $edicionCurso->docente->usuario->persona;
                    }
                    $edicionCurso->curso;
                    array_push($retEdicionesCursos, $edicionCurso);
                }
            }
            // ----
            
            return response()->json($retEdicionesCursos, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
        // devuelve un array de EdicionCurso que correspondan a la Sede especificada
        // devlver Sede, Persona (el docente) y curso de cada EdicionCurso
    }

    /**
     * @OA\Get(
     *     path="/sedes/{id}/examenes",
     *     tags={"Sedes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sede",
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
    public function obtenerExamenes($id){
        // devuelve un array de Examenes que correspondan a la Sede especificada
        // de cada Examen devlver Sede, Usuario (el docente) y curso
        try {
            $RES = [];

            // ---- guarda en $RES los Examen que se asocian al proximo o actual PeriodoExamen
            $idPeriodo = 0;
            $pea  = PeriodoExamen::periodoActual();
            $piea = PeriodoExamen::periodoProximo();
            if ($pea != null) {
                $idPeriodo = $pea->id;
            } else if ($piea != null) {
                $idPeriodo = $piea->id;
            }

            $Sede = Sede::where('id', $id)->first();
            foreach ($Sede->examenes as $id => $examen) {
                if ($examen->periodoExamen->id == $idPeriodo) {
                    if ($examen->docente != null) {
                        $examen->docente->usuario->persona;
                    }
                    $examen->curso;
                    array_push($RES, $examen);
                }
            }
            //foreach ($Sede->carreras as $id => $carrera) {
            //    foreach ($carrera->cursos as $id => $curso) {
            //        foreach ($curso->examenes as $id => $examen) {
            //            // if ($examen->periodoExamen == $idPeriodo) {
            //            if ($examen->docente != null) {
            //                $examen->docente->usuario->persona->id;
            //            }
            //            $examen->curso();
            //            $examen->sede();
            //            array_push($RES, $examen);
            //            // }
            //        }
            //    }
            //}
            return response()->json($RES, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
        return response()->json(["message" => "No implementado aun"], 501);
    }
}
