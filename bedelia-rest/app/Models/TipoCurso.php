<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCurso extends Model
{
    protected $table = "tipo_curso";
    protected $primaryKey = "id";
    protected $fillable = [
        'tipo',
    ];

    public function cursos() {
        return $this->hasMany('App\Models\Curso');
    }
}
