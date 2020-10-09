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

$router->post('AreasEstudio', 'AreaEstudioController@agregar');
$router->get('AreasEstudio/{Id}', 'AreaEstudioController@obtenerUno');
$router->get('AreasEstudio', 'AreaEstudioController@obtenerLista');

$router->post('TipoCurso', 'TipoCursoController@agregar');
$router->get('TipoCurso/{Id}', 'TipoCursoController@obtenerUno');
$router->get('TipoCurso', 'TipoCursoController@obtenerLista');

$router->post('Curso', 'CursoController@agregar');
$router->get('Curso/{Id}', 'CursoController@obtenerUno');
$router->get('Curso', 'CursoController@obtenerLista');

