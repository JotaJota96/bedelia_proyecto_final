<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoLectivo extends Model
{
    protected $table = "periodo_lectivo";
    //aca tambien
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo', 'id', 'id');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->hasMany('App\Models\EdicionCurso');
    }
}
