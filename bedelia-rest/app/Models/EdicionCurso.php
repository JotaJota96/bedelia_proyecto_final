<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EdicionCurso extends Model
{
    protected $table = "edicion_curso";
    protected $primaryKey = "id";
    protected $fillable = [
        'acta_confirmada'
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
    public function docente() {
        return $this->belongsTo('App\Models\Docente');
    }

	// devuelve coleccion
    public function clasesDictada() {
        return $this->hasMany('App\Models\ClaseDictada');
    }

	// devuelve uno
    public function periodoLectivo() {
        return $this->belongsTo('App\Models\PeriodoLectivo');
    }

	// devuelve coleccion
    public function estudiantes() {
        return $this->belongsToMany('App\Models\Estudiante','inscripcion_curso')->withPivot('nota');
    }

    public function contarAsistidas($idEstudiante, $valor = null){
        // si valor = null: suma todo
        // si valor = {0, 0.5, 1}: suma solo las asistencias que tengan ese valor
        $cont = 0;
        foreach ($this->clasesDictada as $claseDictada) {
            foreach ($claseDictada->estudiantes as $estudiante) {
                if ($estudiante->id == $idEstudiante) {
                    $valorAsistencia = $estudiante->pivot->asistencia;

                    // comparo con triple = para distinguir un null de un 0
                    if ($valor === null){
                        $cont += $valorAsistencia;
                    }else if ($valorAsistencia == $valor){
                        $cont++;
                    }
                }
            }
        }
        return $cont;
    }
}
