<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCurso extends Model
{
    protected $table = "tipo_curso";

    protected $fillable = [
        'tipo',
    ];

    public function cursos() {
        return $this->hasMany('App\Models\Curso');
    }
}
