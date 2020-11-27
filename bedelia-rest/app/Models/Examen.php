<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = "examen";
    protected $primaryKey = "id";
    protected $fillable = [
        'acta_confirmada', 'fecha',
    ];

	// devuelve uno
    public function curso() {
        return $this->belongsTo('App\Models\Curso');
    }

	// devuelve uno
    public function sede() {
        return $this->belongsTo('App\Models\Sede');
    }

	// devuelve uno
    public function periodoExamen() {
        return $this->belongsTo('App\Models\PeriodoExamen');
    }

	// devuelve coleccion
    public function estudiantes() {
        return $this->belongsToMany('App\Models\Estudiante','inscripcion_examen')->withPivot('nota');
    }

	// devuelve uno
    public function docente() {
        return $this->belongsTo('App\Models\Docente');
    }

    // ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****

    public function obtenerActa(){
        $res = array (
            "id"              => $this->id,
            "tipo"            => 'EX',
            "acta_confirmada" => $this->acta_confirmada,
            "periodo"         => $this->periodoExamen->periodo->toString(),
            "fecha"           => $this->fecha,
            "curso"           => $this->curso,
            "notas"           => array(),
        );
        foreach ($this->estudiantes as $estudiante) {
            $nota = array (
                "ciEstudiante" => $estudiante->usuario->persona->cedula,
                "nombre" =>       $estudiante->usuario->persona->nombre,
                "apellido" =>     $estudiante->usuario->persona->apellido,
                "nota" =>         $estudiante->pivot->nota,
            );
            array_push($res['notas'], $nota);
        }
        return $res;
    }
}
