<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = "curso";

    protected $fillable = [
        'nombre', 'descripcion', 'max_inasistencias', 'cant_creditos', 'cant_clases',
    ];

    // devuelve coleccion
    // previas de este curso
    public function previas() {
        return $this->hasMany('App\Models\Previa');
    }

    // devuelve coleccion
    // curso que tienen como previa a este curso
    public function previaDe() {
        return $this->hasMany('App\Models\Previa', 'curso_id_previa', 'id');
    }
    
	// devuelve uno
    public function areaEstudio() {
        return $this->belongsTo('App\Models\AreaEstudio');
    }

	// devuelve uno
    public function tipoCurso() {
        return $this->belongsTo('App\Models\TipoCurso');
    }

	// devuelve coleccion
    public function carreras() {
        return $this->belongsToMany('App\Models\Carrera', 'carrera_curso')->withPivot('semestre', 'optativo');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->hasMany('App\Models\EdicionCurso');
    }

	// devuelve coleccion
    public function examenes() {
        return $this->hasMany('App\Models\Examen');
    }

    //--------------------------------------------
    public function seDictaEnSemestreParOInpar(){
        // devuelve 0 si el curso se dicta en ambos semestres
        // devuelve 1 si el curso se dicta solo en semestres inpares
        // devuelve 2 si el curso se dicta solo en semestres pares

        $seDictaEnInpares = false;
        $seDictaEnPares   = false;

        foreach ($this->carreras as $carrera) {
            if ($carrera->pivot->semestre % 2 == 0){
                $seDictaEnPares   = true;
            }else{
                $seDictaEnInpares = true;
            }
        }
        if ($seDictaEnPares && $seDictaEnInpares){
            return 0;
        }elseif ($seDictaEnInpares){
            return 1;
        }elseif ($seDictaEnPares){
            return 2;
        }else{
            throw new \Exception("Error al calcular si la carrera se dicta en semestres pares, inpares o en ambos");
        }
    }

}
