<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\Sede;
use \App\Models\Direccion;

class SedesController extends Controller
{
    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

        /**
     * @OA\Get(
     *     path="/sedes/{id}",
     *     tags={"Sedes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la sede",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos de la sede",
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     * )
     */
    public function obtenerUno(int $id){
        $sede = Sede::find($id);

        if ($sede == null){
            return response()->json(null, 404);
        }
        $sede->direccion;
        return response()->json($sede, 200);
    }

        /**
     * @OA\Get(
     *     path="/sedes/",
     *     tags={"Sedes"},
     *     @OA\Response(
     *         response=200,
     *         description="Todas las sedes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/SedeDTO"),
     *         ),
     *     ),
     * )
     */
    public function obtenerTodos(){
        $sedes = Sede::all();

        foreach ($sedes as $Id => $value) {
            $value->direccion;
        }
        return response()->json($sedes, 200);
    }

        /**
     * @OA\Post(
     *     path="/sedes",
     *     tags={"Sedes"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Devuelve datos de la sede creada",
     *         @OA\JsonContent(ref="#/components/schemas/SedeDTO"),
     *     ),
     * )
     */
    public function agregar(){
        $sede = new Sede();
        $sede->fill($this->request->json()->all());

        $dir = new Direccion();
        $dir->fill($this->request->json('direccion'));

        $sede->direccion()->associate($dir);

        try {
            DB::beginTransaction();

            // inserta la direccion y se la asocia a la persona
            $dir->save();
            $sede->direccion()->associate($dir);
            $sede->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e, 500);
            // devuelve un estado HTTP 500 y un mensaje simple del error
            return response()->json(['error' => 'Error al guardar los datos'], 500);
        }

        return response()->json($sede, 200);
    }
}
