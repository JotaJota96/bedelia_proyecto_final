<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaseDictada extends Model
{
    protected $table = "clase_dictada";

    protected $fillable = [
        'fecha'
    ];

    public function edicionCurso() {
        return $this->belongsTo('App\Models\EdicionCurso');
    }

    public function estudiantes() {
        return $this->belongsToMany('App\Models\Estudiante');
    }
}
