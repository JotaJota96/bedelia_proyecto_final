<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Previa extends Model
{
    protected $table = "previa";
    protected $primaryKey = "id";
    protected $fillable = [
        'tipo',
    ];

	// devuelve uno
    public function carrera() {
        return $this->belongsTo('App\Models\Carrera');
    }

    // devuelve uno
    // curso del que se quiere saber la previa
    public function curso() {
        return $this->belongsTo('App\Models\Curso');
    }

    // devuelve uno
    // curso previo
    public function previa() {
        return $this->belongsTo('App\Models\Curso', 'curso_id_previa', 'id');
    }
}
