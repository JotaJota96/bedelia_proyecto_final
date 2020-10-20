<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Periodo;

class PeriodoExamen extends Model
{
    protected $table = "periodo_examen";
    protected $primaryKey = "id";
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo', 'id', 'id');
    }

	// devuelve coleccion
    public function examenes() {
        return $this->hasMany('App\Models\Examen');
    }

    public static function periodoActual(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'EX')->where('fecha_inicio', '<', $hoy)->where('fecha_fin', '>', $hoy)->orderby('id', 'desc')->first();
        return $PeriodoActual->periodoExamen;
    }

    public static function periodoProximo(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'EX')->where('fecha_inicio', '>', $hoy)->orderby('id', 'asc')->first();
        return $PeriodoActual->periodoExamen;
    }
}
