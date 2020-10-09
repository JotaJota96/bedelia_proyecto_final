<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;

class Usuario extends Model
{
    protected $table = "usuario";
    
    // (id, contrasenia, remember_token, persona_id)
    protected $fillable = [
        'contrasenia', 'remember_token'
    ];

    protected $hidden = [
        'contrasenia', 'remember_token'
    ];

    // --------- funciones de relaciones ---------------------------------------------

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

    // --------- funciones estaticas ---------------------------------------------
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

    // --------- funciones estaticas ---------------------------------------------
    public function roles(){
        $roles = array();

        if ($this->admin){
            array_push($roles, 'admin');
        }
        if ($this->administrativo){
            array_push($roles, 'administrativo');
        }
        if ($this->estudiante){
            array_push($roles, 'estudiante');
        }
        if ($this->docente){
            array_push($roles, 'docente');
        }
        return $roles;
    }

}
