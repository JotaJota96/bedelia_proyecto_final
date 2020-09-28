<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodoLectivo extends Model
{
    protected $table = "periodo_lectivo";
    
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->hasMany('App\Models\EdicionCurso');
    }
}
