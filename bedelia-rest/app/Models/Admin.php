<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = "admin";

    protected $fillable = [];

	public function usuario() {
        return $this->belongsTo('App\Models\Usuario', 'id', 'id');
    }
}
