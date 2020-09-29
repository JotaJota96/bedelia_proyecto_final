<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = "usuario";
    
    // (id, contrasenia, remember_token, persona_id)
    protected $fillable = [];

    protected $hidden = [
        'contrasenia', 'remember_token'
    ];

    public function persona() {
        return $this->hasOne('App\Models\Persona');
    }

	// devuelve uno
    public function admin() {
        return $this->hasOne('App\Models\Admin');
    }

	// devuelve uno
    public function administrativo() {
        return $this->hasOne('App\Models\Administrativo');
    }

	// devuelve uno
    public function estudiante() {
        return $this->hasOne('App\Models\Estudiante');
    }

	// devuelve uno
    public function docente() {
        return $this->hasOne('App\Models\Docente');
    }
}
