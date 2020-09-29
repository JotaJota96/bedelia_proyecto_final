<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = "direccion";
    
    protected $fillable = [
        'departamento', 'ciudad', 'calle', 'numero',
    ];

    // devuelve uno
	public function persona() {
        return $this->belongsTo('App\Models\Persona');
    }
	public function sede() {
        return $this->belongsTo('App\Models\Sede');
    }
}
