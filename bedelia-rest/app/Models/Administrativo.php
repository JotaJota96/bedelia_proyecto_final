<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Administrativo extends Model
{
    protected $table = "administrativo";

    protected $fillable = [];

    public function usuario() {
        return $this->belongsTo('App\Models\Usuario');
    }

    public function sede() {
        return $this->belongsTo('App\Models\Sede');
    }

}
