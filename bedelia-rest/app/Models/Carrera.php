<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = "carrera";

    protected $fillable = [
        'nombre', 'descripcion', 'cant_semestres',
    ];

    public function previas() {
        return $this->hasMany('App\Models\Previa');
    }

    public function sedes() {
        return $this->belongsToMany('App\Models\Sede');
    }

    public function cursos() {
        return $this->belongsToMany('App\Models\Curso', 'carrera_curso')->withPivot('semestre', 'optativo');
    }

    public function postulaciones() {
        return $this->hasMany('App\Models\Postulacion');
    }

    public function areasEstudio() {
        return $this->belongsToMany('App\Models\AreaEstudio', 'carrera_area_estudio')->withPivot('creditos');
    }
    
	// devuelve coleccion
    public function inscripcionesCarrera() {
        return $this->hasMany('App\Models\InscripcionCarrera');
    }
}
