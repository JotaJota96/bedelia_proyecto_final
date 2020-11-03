<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\VarDumper\VarDumper;

class RolMiddleware
{
    /**
     * Identificadores de roles:
     *
     * s => superadmin
     * a => administrativo
     * d => docente
     * e => estudiante
     */
    public function handle($request, Closure $next, $rol = "")
    {
        // usuario disponible en:
        // $request->user()

        $dejarPasar = false;
        $usu = $request->user();

        if (strpos($rol, 's') !== false){
            $dejarPasar = $dejarPasar || $usu->admin != null;
        }
        if (strpos($rol, 'a') !== false){
            $dejarPasar = $dejarPasar || $usu->administrativo != null;
        }
        if (strpos($rol, 'd') !== false){
            $dejarPasar = $dejarPasar || $usu->docente != null;
        }
        if (strpos($rol, 'e') !== false){
            $dejarPasar = $dejarPasar || $usu->estudiante != null;
        }
        
        if ($dejarPasar){
            return $next($request);
        }else{
            return response()->json(['message' => 'Acceso denegado'], 401);
        }
    }
}
