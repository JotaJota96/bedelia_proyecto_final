<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;

class Usuario extends Model
{
    protected $table = "usuario";
    
    // (id, contrasenia, remember_token, persona_id)
    protected $fillable = [];

    protected $hidden = [
        'contrasenia', 'remember_token'
    ];

    public function persona() {
        return $this->belongsTo('App\Models\Persona');
    }

	// devuelve uno
    public function admin() {
        return $this->hasOne('App\Models\Admin', 'id', 'id');
    }

	// devuelve uno
    public function administrativo() {
        return $this->hasOne('App\Models\Administrativo', 'id', 'id');
    }

	// devuelve uno
    public function estudiante() {
        return $this->hasOne('App\Models\Estudiante', 'id', 'id');
    }

	// devuelve uno
    public function docente() {
        return $this->hasOne('App\Models\Docente', 'id', 'id');
    }

    public static function buscar($id){
        $per = Persona::where('cedula', $id)->first();
        if ($per == null){
            $per = Persona::where('correo', $id)->first();
        }
        if ($per == null){
            return null;
        }
        return $per->usuario;
    }

}
