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
        return $this->belongsToMany('App\Models\EdicionCurso','inscripcion_curso');
    }

	// devuelve coleccion
    public function examenes() {
        return $this->belongsToMany('App\Models\Examen','inscripcion_examen');
    }

	// devuelve coleccion
    public function clasesDictada() {
        return $this->belongsToMany('App\Models\ClaseDictada');
    }

	// devuelve coleccion
    public function inscripcionesCarrera() {
        return $this->hasMany('App\Models\InscripcionCarrera');
    }
}
