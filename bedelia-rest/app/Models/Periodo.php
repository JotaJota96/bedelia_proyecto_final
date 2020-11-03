<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = "periodo";
    protected $primaryKey = "id";
    protected $fillable = [
        'fecha_inicio', 'fecha_fin','numero', 'tipo'
    ];

	// devuelve uno
    public function periodoLectivo() {
        return $this->hasOne('App\Models\PeriodoLectivo', 'id', 'id');
    }

	// devuelve uno
    public function periodoInscExamen() {
        return $this->hasOne('App\Models\PeriodoInscExamen', 'id', 'id');
    }

	// devuelve uno
    public function periodoInscCurso() {
        return $this->hasOne('App\Models\PeriodoInscCurso', 'id', 'id');
    }

	// devuelve uno
    public function periodoExamen() {
        return $this->hasOne('App\Models\PeriodoExamen', 'id', 'id');
    }

    //-------------------------------------------
    public function toString(){
        // string con el año del periodo y un "-"
        $ret = date_format(new \DateTime($this->fecha_inicio), "Y") . "-";
        $mes = "";
        switch ($this->numero) {
            case 1: $mes = "Enero";     break;
            case 2: $mes = "Julio";     break;
            case 3: $mes = "Diciembre"; break;
            default:                    break;
        }

        switch ($this->tipo){
            case 'IE': // inscripcion a examenes
                $ret .= "INSC-" . $mes;
                break;
            case 'EX': // examen
                $ret .= $mes;
                break;
            case 'IC': // inscripcion a cursos
                $ret .= "INSC-" . $this->numero . "S";
                break;
            case 'LE': // lectivo
                $ret .= $this->numero . "S";
               break;
            default:
                break;
        }
        return $ret;
    }

    private function nombreMes($mes){
        switch ($mes) {
            case '1':  return "Enero";
            case '2':  return "Febrero";
            case '3':  return "Marzo";
            case '4':  return "Abril";
            case '5':  return "Mayo";
            case '6':  return "Junio";
            case '7':  return "Julio";
            case '8':  return "Agosto";
            case '9':  return "Setiembre";
            case '10': return "Octubre";
            case '11': return "Noviembre";
            case '12': return "Diciembre";
            default:   return "-";
        }
    }
}















