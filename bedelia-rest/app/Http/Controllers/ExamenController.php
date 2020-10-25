<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Examen;
use App\Models\Usuario;
use App\Models\Persona;

class ExamenController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }



    /**
     * @OA\Post(
     *     path="/examenes/{id}/inscripciones/{ciEstudiante}",
     *     tags={"Exámenes"},
     *     description="Inscribe a un estudiante a un exámen",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del exámen",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="ciEstudiante",
     *         in="path",
     *         description="CI del estudiante a inscribir",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description=""
     *     ),
     * )
     */
    
    public function asignarEstudiante($id, $ciEstudiante){
        try {
            DB::beginTransaction();
            $Examen = Examen::where('id', $id)->first();
            $Usuario = Usuario::buscar($ciEstudiante);
            $Usuario->estudiante;
            $datosTablaIntermedia = [
                'nota' => 0,
            ];
            $Usuario->estudiante->examenes()->attach($Examen, $datosTablaIntermedia);
            $Usuario->save();
            DB::commit();
            return response()->json(null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al asignar el Docente.' . $e->getMessage()], 500);
        }
    }
}
