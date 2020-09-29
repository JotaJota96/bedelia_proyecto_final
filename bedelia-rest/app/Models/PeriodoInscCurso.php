<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoInscCurso extends Model
{
    protected $table = "periodo_insc_curso";
    
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo', 'id', 'id');
    }
}
