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
        return $this->belongsToMany('App\Models\Estudiante');
    }

	// devuelve uno
    public function docente() {
        return $this->belongsTo('App\Models\Docente');
    }
}
