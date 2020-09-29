<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = "docente";

    protected $fillable = [];

	// devuelve uno
	public function usuario() {
        return $this->belongsTo('App\Models\Usuario', 'id', 'id');
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
