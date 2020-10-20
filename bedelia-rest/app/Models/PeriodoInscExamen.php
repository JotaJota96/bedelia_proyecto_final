<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Periodo;

class PeriodoInscExamen extends Model
{
    protected $table = "periodo_insc_examen";
    protected $primaryKey = "id";
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo', 'id', 'id');
    }

    public function periodoExamen() {
        // devuelve el PeriodoExamen para el que se realizan las inscripciones en este periodo
        $fecha = $this->periodo->fecha_inicio;
        $PeriodoEX = Periodo::where('tipo', 'EX')->where('fecha_inicio', '>', $fecha)->orderby('id', 'asc')->first();
        return $PeriodoEX->periodoExamen();
    }

    public static function periodoActual(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'IE')->where('fecha_inicio', '<', $hoy)->where('fecha_fin', '>', $hoy)->orderby('id', 'desc')->first();
        return $PeriodoActual->periodoInscExamen;
    }

    public static function periodoProximo(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'IE')->where('fecha_inicio', '>', $hoy)->orderby('id', 'asc')->first();
        return $PeriodoActual->periodoInscExamen;
    }
}
