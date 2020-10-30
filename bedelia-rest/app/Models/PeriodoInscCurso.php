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
    
    // devuelve el PeriodoLectivo para el que se realizan las inscripciones en este periodo
    public function periodoLectivo() {
        $fecha = $this->periodo->fecha_inicio;
        $PeriodoLE = Periodo::where('tipo', 'LE')->where('fecha_inicio', '>=', $fecha)->orderby('id', 'asc')->first();
        return $PeriodoLE->periodoLectivo();
    }

    // Devuelve el periodo actual segun la fecha, o null si no se encontro ninguno
    public static function periodoActual(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'IC')->where('fecha_inicio', '<=', $hoy)->where('fecha_fin', '>=', $hoy)->orderby('id', 'desc')->first();
        if ($PeriodoActual == null) {
            return null;
        }
        return $PeriodoActual->periodoInscCurso;
    }

    // Devuelve el proximo periodo segun la fecha, o null si no se encontro ninguno
    public static function periodoProximo(){
        $hoy = date('Y-m-d');
        $PeriodoProximo = Periodo::where('tipo', 'IC')->where('fecha_inicio', '>', $hoy)->orderby('id', 'asc')->first();
        if ($PeriodoProximo == null) {
            return null;
        }
        return $PeriodoProximo->periodoInscCurso;
    }

    // Devuelve el periodo anterior segun la fecha, o null si no se encontro ninguno
    public static function periodoAnterior(){
        $hoy = date('Y-m-d');
        $PeriodoProximo = Periodo::where('tipo', 'IC')->where('fecha_fin', '<', $hoy)->orderby('id', 'desc')->first();
        if ($PeriodoProximo == null) {
            return null;
        }
        return $PeriodoProximo->periodoInscCurso;
    }
}
