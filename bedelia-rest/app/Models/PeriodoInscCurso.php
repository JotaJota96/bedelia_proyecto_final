<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Periodo;

class PeriodoInscCurso extends Model
{
    protected $table = "periodo_insc_curso";
    protected $primaryKey = "id";
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo', 'id', 'id');
    }

    public function periodoLectivo() {
        // devuelve el PeriodoLectivo para el que se realizan las inscripciones en este periodo
        $fecha = $this->periodo->fecha_inicio;
        $PeriodoLE = Periodo::where('tipo', 'LE')->where('fecha_inicio', '>', $fecha)->orderby('id', 'asc')->first();
        return $PeriodoLE->periodoLectivo();
    }

    public static function periodoActual(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'IC')->where('fecha_inicio', '<', $hoy)->where('fecha_fin', '>', $hoy)->orderby('id', 'desc')->first();
        return $PeriodoActual->periodoInscCurso;
    }

    public static function periodoProximo(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'IC')->where('fecha_inicio', '>', $hoy)->orderby('id', 'asc')->first();
        return $PeriodoActual->periodoInscCurso;
    }
}
