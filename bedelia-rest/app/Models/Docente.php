<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = "docente";

    protected $fillable = [];

	// devuelve uno
	public function usuario() {
        return $this->belongsTo('App\Models\Usuario');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->hasMany('App\Models\EdicionCurso');
    }

	// devuelve coleccion
    public function examenes() {
        return $this->hasMany('App\Models\Examen');
    }
}
