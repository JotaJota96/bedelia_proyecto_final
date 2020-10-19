<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = "direccion";
    protected $primaryKey = "id";
    protected $fillable = [
        'departamento', 'ciudad', 'calle', 'numero',
    ];

    // devuelve uno
	public function persona() {
        return $this->hasOne('App\Models\Persona');
    }
	public function sede() {
        return $this->hasOne('App\Models\Sede');
    }
}
