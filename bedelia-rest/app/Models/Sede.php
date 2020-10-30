<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = "sede";
    protected $primaryKey = "id";
    protected $fillable = [
        'telefono',
    ];

    public function carreras() {
        return $this->belongsToMany('App\Models\Carrera');
    }

	// devuelve coleccion
    public function postulaciones() {
        return $this->hasMany('App\Models\Postulacion');
    }

	// devuelve uno
    public function direccion() {
        return $this->belongsTo('App\Models\Direccion');
    }

	// devuelve coleccion
    public function inscripcionesCarrera() {
        return $this->hasMany('App\Models\InscripcionCarrera');
    }

	// devuelve coleccion
    public function administrativos() {
        return $this->hasMany('App\Models\Administrativo');
    }

	// devuelve coleccion
    public function edicionesCurso() {
        return $this->hasMany('App\Models\EdicionCurso');
    }
	// devuelve coleccion
    public function examenes() {
        return $this->hasMany('App\Models\Examen');
    }

    // ***** ***** ***** ***** ***** ***** ***** ***** ***** ***** *****

    public function obtenerActas($tipo, $idPeriodo){
        $actas = array();
        if (strcmp($tipo, "LE") == 0){
            $edicionCurso = $this->edicionesCurso()->where('periodo_lectivo_id', $idPeriodo)->get();
            foreach ($edicionCurso as $value){
                array_push($actas, $value->obtenerActa());
            }
        }
        if (strcmp($tipo, "EX") == 0){
            $examen = $this->examenes()->where('periodo_examen_id', $idPeriodo)->get();
            foreach ($examen as $value){
                array_push($actas, $value->obtenerActa());
            }
        }
        return $actas;
    }

}
