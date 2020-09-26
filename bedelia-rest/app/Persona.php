<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'ci', 'nombre', 'apellido', 'correo', 'fecha_nac', 'sexo',
    ];

    public function direccion() {
        return $this->hasOne('App\Direccion');
    }

    public function usuario() {
        return $this->hasOne('App\Usuario');
    }
}
