<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = "persona";

    protected $fillable = [
        'ci', 'nombre', 'apellido', 'correo', 'fecha_nac', 'sexo',
    ];

	// devuelve uno
    public function direccion() {
        return $this->hasOne('App\Models\Direccion');
    }

	// devuelve uno
    public function usuario() {
        return $this->belongsTo('App\Models\Usuario');
    }

	// devuelve coleccion
    public function postulaciones() {
        return $this->hasMany('App\Models\Postulacion');
    }
}
