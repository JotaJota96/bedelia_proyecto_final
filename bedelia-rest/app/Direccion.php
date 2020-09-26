<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = "direccion";
    
    protected $fillable = [
        'departamento', 'ciudad', 'calle', 'numero',
    ];

    public function persona() {
        return $this->belongsTo('App\Persona');
    }
}
