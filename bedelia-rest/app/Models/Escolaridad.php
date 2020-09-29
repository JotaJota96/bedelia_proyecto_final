<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escolaridad extends Model
{
    protected $table = "escolaridad";

    protected $primaryKey = 'clave';

    protected $fillable = [
        'archivo', 'fecha',
    ];

}
