<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodoExamen extends Model
{
    protected $table = "periodo_examen";
    
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo');
    }

	// devuelve coleccion
    public function examenes() {
        return $this->hasMany('App\Models\Examen');
    }
}
