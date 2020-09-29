<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = "curso";

    protected $fillable = [
        'nombre', 'descripcion', 'max_inasistencias', 'cant_créditos', 'cant_clases',
    ];

    // devuelve coleccion
    // previas de este curso
    public function previas() {
        return $this->hasMany('App\Models\Previa');
    }

    // devuelve coleccion
    // curso que tienen como previa a este curso
    public function previaDe() {
        return $this->hasMany('App\Models\Previa', 'curso_id_previa', 'id');
    }
    
	// devuelve uno
    public function areaEstudio() {
        return $this->belongsTo('App\Models\AreaEstudio');
    }

	// devuelve uno
    public function tipoCurso() {
        return $this->belongsTo('App\Models\TipoCurso');
    }

	// devuelve coleccion
    public function carreras() {
        return $this->belongsToMany('App\Models\Carrera');
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
