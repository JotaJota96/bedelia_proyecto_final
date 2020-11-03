<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = "estudiante";
    protected $primaryKey = "id";
    protected $fillable = [];

    public function usuario() {
        return $this->belongsTo('App\Models\Usuario', 'id', 'id');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->belongsToMany('App\Models\EdicionCurso','inscripcion_curso')->withPivot('nota');
    }

	// devuelve coleccion
    public function examenes() {
        return $this->belongsToMany('App\Models\Examen','inscripcion_examen')->withPivot('nota');
    }

	// devuelve coleccion
    public function clasesDictada() {
        return $this->belongsToMany('App\Models\ClaseDictada','asistencia')->withPivot('asistencia');
    }

	// devuelve coleccion
    public function inscripcionesCarrera() {
        return $this->hasMany('App\Models\InscripcionCarrera');
    }

    public function NotasCarrera($idCarrera, $filtrar = true, $soloActasConfirmadas = false){
        $res = [];
        foreach ($this->edicionesCurso as $value) {
            if ($value->curso->carreras->find($idCarrera)!=null) {
                if ( $soloActasConfirmadas == false || ($soloActasConfirmadas == true && $value->acta_confirmada == true)){
                    $Item = array (
                        "curso_id"         => $value->curso->id,
                        "edicion_curso_id" => $value->id,
                        "acta_confirmada"  => $value->acta_confirmada,
                        "periodo_id"       => $value->periodoLectivo->id,
                        "nota"             => $value->pivot->nota,
                        "cant_asistencias" => $value->contarAsistidas($this->id),
                    );
                    array_push($res, $Item);
                }
            }
        }
        if ($filtrar){
            $res = $this->filtrarMasReciente($res);
        }
        return $res;
    }
    //php artisan tinker
    public function NotasExamenes($idCarrera, $filtrar = true, $soloActasConfirmadas = false){
        $res = [];
        foreach ($this->examenes as $value) {
            if ($value->curso->carreras->find($idCarrera) != null) {
                if ( $soloActasConfirmadas == false || ($soloActasConfirmadas == true && $value->acta_confirmada == true)){
                    $Item = array (
                        "curso_id"        => $value->curso->id,
                        "examen_id"       => $value->id,
                        "acta_confirmada" => $value->acta_confirmada,
                        "periodo_id"      => $value->periodoExamen->id,
                        "nota"            => $value->pivot->nota,
                    );
                    array_push($res, $Item);
                }
            }
        }
        if ($filtrar){
            $res = $this->filtrarMasReciente($res);
        }
        return $res;
    }
    private function filtrarMasReciente($arr){
        // Dado un array con el siguiente formato,  devuelve un array solo con los elementos mas recientes de cada curso
        // [
        //     "curso_id" => 1,
        //     "periodo_id" => 4,
        //     "nota" => 2.21,
        //     "cant_asistencias" => 20,
        // ],
    
        $arrRet = array();
        $auxClasificado = array();
        foreach ($arr as $value) {
            if ( ! array_key_exists($value['curso_id'], $auxClasificado)){
                $auxClasificado[$value['curso_id']] = array();
            }
            array_push($auxClasificado[$value['curso_id']], $value);
        }
        
        foreach ($auxClasificado as $key => $value) {
            $masReciente = $value[0];
            foreach ($value as $item) {
                if ($item['periodo_id'] > $masReciente['periodo_id']){
                    $masReciente = $item;
                }
            }
            array_push($arrRet, $masReciente);
        }
        return $arrRet;
    }


    public function calcularEscolaridad($idCarrera){
        // obtengo y verifico Carrera
        $carrera = Carrera::find($idCarrera);
        if ($carrera == null){
            throw new \Exception("Carrera no encontrada");
        }
        if ($this->inscripcionesCarrera()->where('carrera_id', $idCarrera)->first() == null){
            throw new \Exception("El estudiante no está inscripto en la carrera");
        }

        
        $cursos = array();
        foreach ($carrera->cursos as $value) $cursos[$value->id] = $value;        

        //----  obtener la informacion --------------------

        // cargo los datos del usuario
        $this->usuario->persona->direccion;

        // sera un array asociativo para asociar la actividad del estudiante al semestre correspondiente
        // key: numero de semestre, value: array de notas
        $semestres = array();
        // obtengo los cursos y examenes a los que se inscribio el estudiante
        $cursosYExamenesTomados = [
            $this->NotasCarrera($idCarrera, false),
            $this->NotasExamenes($idCarrera, false),
        ];

        foreach ($cursosYExamenesTomados as $coet) {
            foreach ($coet as $value) {
                $numSem = $cursos[$value['curso_id']]->pivot->semestre;
                if ( ! array_key_exists($numSem, $semestres)){
                    $semestres[$numSem] = array();
                }
                array_push($semestres[$numSem], $value);
            }
        }

        // calculo el promedio
        // obtengo las actas confirmadas mas recientes y las referencio por el ID del curso
        // y despues hago el calculo
        $notaPromedio = 0;
        $promediar = array();
        foreach ($this->NotasCarrera($idCarrera, true, true) as $value) {
            $promediar[$value['curso_id']] = $value['nota'];
        }
        foreach ($this->NotasExamenes($idCarrera, true, true) as $value) {
            $promediar[$value['curso_id']] = $value['nota'];
        }
        foreach ($promediar as $key => $value) {
            $notaPromedio += $value;
        }
        $count = count($promediar);
        $notaPromedio = $count == 0 ? 0.0 : $notaPromedio / count($promediar);
        $notaPromedio = round($notaPromedio, 2);
        
        //----  formato y limpieza de la informacion --------------------
        
        $escolaridad = [
            "usuario"       => $this->usuario,          // objeto Usuario
            "carrera"       => $carrera,      // objeto Carrera
            "nota_promedio" => $notaPromedio, // nota promedio
            "semestres"     => [],
        ];
        unset($escolaridad['carrera']->cursos);

        foreach ($semestres as $numSem => $detalle) {
            $sem = [
                "numero"  => $numSem, // numero de semestre
                "detalle" => [],
            ];

            foreach ($detalle as $value) {
                $c = $cursos[$value['curso_id']];
                unset($c->pivot);

                $t = "-";
                if (array_key_exists('edicion_curso_id', $value)) $t = 'LE';
                if (array_key_exists('examen_id', $value)) $t = 'EX';
                
                $p = Periodo::find($value['periodo_id'])->toString();
                
                $n = $value['nota'];
                
                $linea = [
                    "curso"   => $c, // objeto Curso
                    "tipo"    => $t, // "EX" si se trata de un examen o "LE" si se trata de un edicion curso
                    "periodo" => $p, // formato: "2019-1S" para periodo lectivo o "2019-Julio" para periodo examen
                    "nota"    => $n, // nota obtenida
                ];
                array_push($sem['detalle'], $linea);
            }

            array_push($escolaridad['semestres'], $sem);
        }
        
        return $escolaridad;
    }

}
