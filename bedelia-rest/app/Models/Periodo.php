<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = "periodo";

    protected $fillable = [
        'fecha_inicio', 'fecha_fin', 'tipo'
    ];

	// devuelve uno
    public function periodoLectivo() {
        return $this->hasOne('App\Models\PeriodoLectivo', 'id', 'id');
    }

	// devuelve uno
    public function periodoInscExamen() {
        return $this->hasOne('App\Models\PeriodoInscExamen', 'id', 'id');
    }

	// devuelve uno
    public function periodoInscCurso() {
        return $this->hasOne('App\Models\PeriodoInscCurso', 'id', 'id');
    }

	// devuelve uno
    public function periodoExamen() {
        return $this->hasOne('App\Models\PeriodoExamen', 'id', 'id');
    }
}




