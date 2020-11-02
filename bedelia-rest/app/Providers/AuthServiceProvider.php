<?php

namespace App\Providers;

use App\Models\Usuario;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    private $admin = [
    ];
    private $administrativo = [
    ];
    private $docente = [
    ];
    private $estudiante = [
    ];

    public function register() {
    }

    public function boot($rol = null) {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        
        $this->app['auth']->viaRequest('api', function ($request) {
            $token  = "";
            $header = $request->header('Authorization');
            
            if (Str::startsWith($header, 'Bearer ')) {
                $token = Str::substr($header, 7);
            }

            if ($token) {
                $usu = Usuario::where('remember_token', $token)->first();
                if ($usu == null) return null;

                if ($usu->admin){
                    foreach ($this->admin as $value) {
                        if ($request->is($value)) {
                            return $usu;
                        }
                    }
                }
                if ($usu->administrativo){
                    foreach ($this->administrativo as $value) {
                        if ($request->is($value)) {
                            return $usu;
                        }
                    }
                }
                if ($usu->docente){
                    foreach ($this->docente as $value) {
                        if ($request->is($value)) {
                            return $usu;
                        }
                    }
                }
                if ($usu->estudiante){
                    foreach ($this->estudiante as $value) {
                        if ($request->is($value)) {
                            return $usu;
                        }
                    }
                }
            }
            return null;
        });
    }
    
    private function algunaRutaHaceMatch($request, $rutas){
        foreach ($rutas as $value) {
            if ($request->is($value)) {
                return true;
            }
        }
        return false;
    }
}
