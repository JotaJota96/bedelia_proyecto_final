<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrativo extends Model
{
    protected $table = "administrativo";

    protected $fillable = [];

    public function usuario() {
        return $this->belongsTo('App\Models\Usuario', 'id', 'id');
    }

    public function sede() {
        return $this->belongsTo('App\Models\Sede');
    }

}
