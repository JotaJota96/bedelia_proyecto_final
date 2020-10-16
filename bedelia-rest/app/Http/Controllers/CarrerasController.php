<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Carrera;
use App\Models\Sede;
use App\Models\AreaEstudio;
use App\Models\Curso;
use App\Models\Previa;

class CarrerasController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    
    /**
     * @OA\Get(
     *     path="/carreras/{id}",
     *     tags={"Carreras"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/CarreraDTO"),
     *     ),
     * )
     */
    public function obtenerUno(int $Id){
        $carrera = Carrera::find($Id);
        if ($carrera == null){
            return response()->json(null, 404);
        }
        // obtengo mas info que hay que devolver
        $carrera->sedes;
        foreach ($carrera->sedes as $Id => $value) {
            $value->direccion;
            unset($value->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
        }
        $carrera->areasEstudio;
        foreach ($carrera->areasEstudio as $Id => $value) {
            $value->creditos = $value->pivot->creditos;
            unset($value->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
        }
        
        return response()->json($carrera, 200);
    }

    /**
     * @OA\Get(
     *     path="/carreras",
     *     tags={"Carreras"},
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CarreraDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerTodos(){
        $carreras = Carrera::all();

        // obtengo mas info que hay que devolver
        foreach ($carreras as $Id => $c) {
            $c->sedes;
            foreach ($c->sedes as $Id => $s) {
                $s->direccion;
                unset($s->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
            }

            $c->areasEstudio;
            foreach ($c->areasEstudio as $Id => $value) {
                $value->creditos = $value->pivot->creditos;
                unset($value->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
            }
            
        }
        return response()->json($carreras, 200);
    }

    /**
     * @OA\Get(
     *     path="/carreras/{id}/cursos",
     *     tags={"Carreras"},
     *     description="Devuelve la lista de cursos que conforman la carrera",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CursoDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerCursosDeCarrera(int $Id){
        $carrera = Carrera::find($Id);
        if ($carrera == null){
            return response()->json(null, 404);
        }
        // obtengo la lista de cursos
        $cursos = $carrera->cursos;

        foreach ($cursos as $Id => $value) {
            $value->optativo = $value->pivot->optativo;
            $value->semestre = $value->pivot->semestre;
            unset($value->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
        }
        return response()->json($cursos, 200);
    }

    /**
     * @OA\Get(
     *     path="/carreras/{id}/previas",
     *     tags={"Carreras"},
     *     description="Devuelve las relaciones de previas entre los cursos que conforman la carrera",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PreviaDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerPreviasEntreCursosDeCarrera(int $Id){
        $carrera = Carrera::find($Id);
        if ($carrera == null){
            return response()->json(null, 404);
        }
        // obtengo la lista de cursos
        $previas = $carrera->previas;

        return response()->json($previas, 200);
    }
    
    /**
     * @OA\Post(
     *     path="/carreras/",
     *     tags={"Carreras"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/CarreraCreateDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/CarreraDTO"),
     *     ),
     * )
     */
    public function agregar(){
        // Pseudocodigo de esta funcion:
        // Extraer los datos basicos de la carrera
        // Extraer y guardar provisoriamente los datos de la relacion con areas de estudio
        // Extraer y guardar provisoriamente los datos de la relacion con sedes
        // Extraer y guardar provisoriamente los datos de la relacion con los cursos
        // Extraer y guardar provisoriamente los datos de las previas entre los cursos
        // Abrir una TRANSACCION de base de datos
        //     Guardar los datos basicos de la carrera
        //     Relacionar la nueva carrera a las areas de estudio
        //     Relacionar la nueva carrera a los cursos
        //     Guardar las relaciones de previas entre los cursos
        // Si no hubo ningun error, hacer COMMIT y devolver el nuevo curso
        // Si hubo algun error, hacer ROLLBACK y devolver error 500

        // reasigno para trabajar mas comodo
        $req = $this->request;

        // extraigo datos basicos de la carrera
        $carrera = new Carrera();
        $carrera->fill($req->json()->all());

        // extraigo el array de areas de estudio y verifico que existan
        $areasEstudio = array();
        foreach ($req->json('areas_estudio') as $key => $value) {
            $a = AreaEstudio::find($value['id']);
            if ($a == null){
                return $this->responder500("No existe un area de estudio con id = " . $value['id']);
            }
            $a->creditos = $value['creditos']; // atributo ficticio
            array_push($areasEstudio, $a);
        }

        // extraigo el array de sedes y verifico que existan
        $sedes = array();
        foreach ($req->json('sedes') as $key => $value) {
            $s = Sede::find($value['id']);
            if ($s == null){
                return $this->responder500("No existe una sede con id = " . $value['id']);
            }
            array_push($sedes, $s);
        }

        // extraigo el array de cursos y verifico que existan
        $cursos = array();
        foreach ($req->json('cursos') as $key => $value) {
            $c = Curso::find($value['id']);
            if ($c == null){
                return $this->responder500("No existe un curso con id = " . $value['id']);
            }
            // atributos ficticios
            $c->semestre = $value['semestre'];
            $c->optativo = $value['optativo'];
            //array_push($cursos, $c);
            $cursos[$c->id] = $c;
        }

        // extraigo el array de previas
        $previas = array();
        foreach ($req->json('previas') as $key => $value) {
            $p = new Previa();
            $p->fill($value);
            $p->curso_id        = $value['curso_id'];
            $p->curso_id_previa = $value['curso_id_previa'];
            $p->tipo            = $value['tipo'];
            
            // verificacion de existencia de los cursos a relacionar en la previa
            $cursoNoPertenece1 = !(array_key_exists($p->curso_id, $cursos));
            $cursoNoPertenece2 = !(array_key_exists($p->curso_id_previa, $cursos));
            if ($cursoNoPertenece1 || $cursoNoPertenece2){
                return $this->responder500("La previa contiene cursos que no pertenecen a la carrera: {" . $p->curso_id . ", " . $p->curso_id_previa . "}");
            }

            // verificacion de semestres de los cursos a relacionar en la previa
            $c  = $cursos[$p->curso_id];
            $cp = $cursos[$p->curso_id_previa];
            if ($cp->semestre >= $c->semestre){
                return $this->responder500("El curso con id = $p->curso_id_previa debe dictarse antes que el curso con id = $p->curso_id}");
            }
            array_push($previas, $p);
        }

        try {
            DB::beginTransaction();

            // guarda la carrera
            $carrera->save();

            // guarda relaciones entre carrera y area de estudio
            foreach ($areasEstudio as $key => $value) {
                $datosTablaIntermedia = [
                    'creditos' => $value->creditos
                ];
                $carrera->areasEstudio()->attach($value, $datosTablaIntermedia);
            }

            // guarda relaciones entre carrera y sedes
            foreach ($sedes as $key => $value) {
                $datosTablaIntermedia = [
                ];
                $carrera->sedes()->attach($value, $datosTablaIntermedia);
            }

            // guarda las relaciones entre la nueva carrera a los cursos
            foreach ($cursos as $key => $value) {
                $datosTablaIntermedia = [
                    'semestre' => $value->semestre,
                    'optativo' => $value->optativo
                ];
                $carrera->cursos()->attach($value, $datosTablaIntermedia);
            }

            // guarda las relaciones de previas entre los cursos
            foreach ($previas as $key => $value) {
                $curso       = Curso::find($value->curso_id);
                $cursoPrevio = Curso::find($value->curso_id_previa);
                $value->carrera()->associate($carrera);
                $value->curso()->associate($curso);
                $value->previa()->associate($cursoPrevio);
                $value->save();
            }


            // cargo datos a devolver
            foreach ($carrera->sedes as $Id => $s) {
                $s->direccion;
                unset($s->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
            }

            foreach ($carrera->areasEstudio as $Id => $value) {
                $value->creditos = $value->pivot->creditos;
                unset($value->pivot); // quito el atributo 'pivot' porque no lo quiero devolver
            }

            //$carrera->cursos;
            //$carrera->previas;

            DB::commit();
            return response()->json($carrera, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            // devuelve un estado HTTP 500 y un mensaje simple del error
            return $this->responder500("Error al guardar los datos");
        }
    }

    function responder500(string $message){
        return response()->json(['message' => $message], 500);
    }
}

/*
// objeto de prueba para la funcion de crear
{
  "nombre": "Testing",
  "descripcion": "Carrera para probar una funcionalidad",
  "cant_semestres": 2,
  "areas_estudio": [
    { "id": 1, "creditos": 18 },
    { "id": 4, "creditos": 16 }
  ],
  "cursos": [
    {"id": 2, "semestre": 1, "optativo": false },
    {"id": 1, "semestre": 1, "optativo": false },
    {"id": 5, "semestre": 2, "optativo": false },
    {"id": 7, "semestre": 2, "optativo": false }
  ],
  "sedes": [
      { "id": 2 },
      { "id": 3 }
  ]
  "previas": [
    { "curso_id": 5, "curso_id_previa": 1, "tipo": "examen" },
    { "curso_id": 5, "curso_id_previa": 2, "tipo": "curso" },
    { "curso_id": 7, "curso_id_previa": 1, "tipo": "examen" },
    { "curso_id": 7, "curso_id_previa": 2, "tipo": "curso" }
  ]
}

*/