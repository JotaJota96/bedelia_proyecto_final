<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Periodo;

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

    public static function periodoActual(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'LE')->where('fecha_inicio', '<', $hoy)->where('fecha_fin', '>', $hoy)->orderby('id', 'desc')->first();
        return $PeriodoActual;
    }
}
