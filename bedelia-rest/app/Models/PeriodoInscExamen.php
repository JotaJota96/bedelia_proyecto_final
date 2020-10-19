<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Periodo;

class PeriodoInscExamen extends Model
{
    protected $table = "periodo_insc_examen";
    
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo', 'id', 'id');
    }

    public static function periodoActual(){
        $hoy = date('Y-m-d');
        $PeriodoActual = Periodo::where('tipo', 'IE')->where('fecha_inicio', '<', $hoy)->where('fecha_fin', '>', $hoy)->orderby('id', 'desc')->first();
        return $PeriodoActual;
    }
}
