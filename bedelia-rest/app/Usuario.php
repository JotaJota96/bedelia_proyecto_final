<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    // (id, contrasenia, remember_token, persona_id)
    protected $fillable = [];

    protected $hidden = [
        'contrasenia', 'remember_token'
    ];

    public function persona() {
        return $this->belongsTo('App\Persona');
    }

}
