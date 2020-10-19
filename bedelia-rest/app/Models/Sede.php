<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = "sede";
    protected $primaryKey = "id";
    protected $fillable = [
        'telefono',
    ];

    public function carreras() {
        return $this->belongsToMany('App\Models\Carrera');
    }

	// devuelve coleccion
    public function postulaciones() {
        return $this->hasMany('App\Models\Postulacion');
    }

	// devuelve uno
    public function direccion() {
        return $this->belongsTo('App\Models\Direccion');
    }

	// devuelve coleccion
    public function inscripcionesCarrera() {
        return $this->hasMany('App\Models\InscripcionCarrera');
    }

	// devuelve coleccion
    public function administrativos() {
        return $this->hasMany('App\Models\Administrativo');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->hasMany('App\Models\EdicionCurso');
    }
}
