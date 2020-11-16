<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    protected $table = "postulacion";
    protected $primaryKey = "id";
    protected $fillable = [
        'img_ci', 'img_escolaridad', 'img_carne_salud', 'estado',
    ];

	// devuelve uno
    public function persona() {
        return $this->belongsTo('App\Models\Persona');
    }

	// devuelve uno
    public function carrera() {
        return $this->belongsTo('App\Models\Carrera');
    }

	// devuelve uno
    public function sede() {
        return $this->belongsTo('App\Models\Sede');
    }
}
