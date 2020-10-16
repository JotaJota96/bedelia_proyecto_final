<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('usuarios/login', 'UsuarioController@login');
$router->get('usuarios/{ci}', 'UsuarioController@obtenerUno');
$router->get('usuarios', 'UsuarioController@obtenerTodos');
$router->post('usuarios', 'UsuarioController@agregar');

$router->post('areasEstudio', 'AreaEstudioController@agregar');
$router->get('areasEstudio/{Id}', 'AreaEstudioController@obtenerUno');
$router->get('areasEstudio', 'AreaEstudioController@obtenerLista');

$router->post('tipoCurso', 'TipoCursoController@agregar');
$router->get('tipoCurso/{Id}', 'TipoCursoController@obtenerUno');
$router->get('tipoCurso', 'TipoCursoController@obtenerLista');

$router->post('curso', 'CursoController@agregar');
$router->get('curso/{Id}', 'CursoController@obtenerUno');
$router->get('curso', 'CursoController@obtenerLista');

$router->post('periodos', 'PeriodoLectivoController@agregar');
// $router->get('Periodos/{Id}', 'PeriodoLectivoController@obtenerUno');
$router->get('periodos', 'PeriodoLectivoController@obtenerLista');
