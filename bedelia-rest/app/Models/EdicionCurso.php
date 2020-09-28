<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdicionCurso extends Model
{
    protected $table = "edicion_curso";

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
        return $this->belongsToMany('App\Models\Estudiante');
    }
}
