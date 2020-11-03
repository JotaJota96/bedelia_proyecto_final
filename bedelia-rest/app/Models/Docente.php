<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = "docente";
    protected $primaryKey = "id";
    protected $fillable = [];

	// devuelve uno
	public function usuario() {
        return $this->belongsTo('App\Models\Usuario', 'id', 'id');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->hasMany('App\Models\EdicionCurso');
    }

	// devuelve coleccion
    public function examenes() {
        return $this->hasMany('App\Models\Examen');
    }

    public function edicionesCursoActuales() {
        $PeriodoLetivoActual = PeriodoLectivo::periodoActual();
        if ($PeriodoLetivoActual == null) {
            $PeriodoLetivoActual = PeriodoLectivo::periodoProximo();
        }
        if ($PeriodoLetivoActual == null) {
            return [];
        }
        return $this->edicionesCurso->where('periodo_lectivo_id', $PeriodoLetivoActual->id);
    }

    public function examenesActuales() {
        $PeriodoExamenActual = PeriodoExamen::periodoActual();
        if ($PeriodoExamenActual == null) {
            $PeriodoExamenActual = PeriodoExamen::periodoProximo();
        }
        if ($PeriodoExamenActual == null) {
            return [];
        }
        return $this->examenes->where('periodo_examen_id', $PeriodoExamenActual->id);
    }
}
