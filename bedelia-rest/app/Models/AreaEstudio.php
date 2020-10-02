<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaEstudio extends Model
{
    protected $table = "area_estudio";

    protected $fillable = [
        'area',
    ];

    public function cursos() {
        return $this->hasMany('App\Models\Curso');
    }

    public function carreras() {
        return $this->belongsToMany('App\Models\Carrera', 'carrera_area_estudio')->withPivot('creditos');
    }
}
