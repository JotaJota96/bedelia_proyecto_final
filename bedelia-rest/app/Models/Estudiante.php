<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = "estudiante";

    protected $fillable = [];

    public function usuario() {
        return $this->belongsTo('App\Models\Usuario');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->belongsToMany('App\Models\EdicionCurso');
    }

	// devuelve coleccion
    public function examenes() {
        return $this->belongsToMany('App\Models\Examen');
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
