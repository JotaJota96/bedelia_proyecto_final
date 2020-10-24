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

    public function NotasCarrera($idCarrera){
        $res = [];
        foreach ($this->edicionesCurso as $value) {
            if ($value->curso->carreras->find($idCarrera)!=null) {
                $Item = array (
                    "curso_id"        => $value->curso->id,
                    "periodo_id"      => $value->periodoLectivo->id,
                    "nota"            => $value->pivot->nota,
                    "cant_asistencias"=> $value->contarAsistidas($this->id),
                );
                array_push($res, $Item);
            }
        }
        $res = $this->filtrarMasReciente($res);
        return $res;
    }
    //php artisan tinker
    public function NotasExamenes($idCarrera){
        $res = [];
        foreach ($this->examenes as $value) {
            if ($value->curso->carreras->find($idCarrera) != null) {
                $Item = array (
                    "curso_id"        => $value->curso->id,
                    "periodo_id"      => $value->periodoExamen->id,
                    "nota"            => $value->pivot->nota,
                );
                array_push($res, $Item);
            }
        }
        $res = $this->filtrarMasReciente($res);
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
}
