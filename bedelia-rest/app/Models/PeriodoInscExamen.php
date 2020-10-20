<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoInscExamen extends Model
{
    protected $table = "periodo_insc_examen";
    protected $primaryKey = "id";
    protected $fillable = [];

	// devuelve uno
	public function periodo() {
        return $this->belongsTo('App\Models\Periodo', 'id', 'id');
    }
}
