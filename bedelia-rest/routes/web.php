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
$router->get('usuarios/{ci}',   'UsuarioController@obtenerUno');
$router->get('usuarios',        'UsuarioController@obtenerTodos');
$router->post('usuarios',       'UsuarioController@agregar');

$router->get( 'sedes/{id}', 'SedesController@obtenerUno');
$router->get( 'sedes',      'SedesController@obtenerTodos');
$router->post('sedes',      'SedesController@agregar');

$router->post('areasEstudio', 'AreaEstudioController@agregar');
$router->get('areasEstudio/{Id}', 'AreaEstudioController@obtenerUno');
$router->get('areasEstudio', 'AreaEstudioController@obtenerLista');

$router->post('tiposCurso', 'TipoCursoController@agregar');
$router->get('tiposCurso/{Id}', 'TipoCursoController@obtenerUno');
$router->get('tiposCurso', 'TipoCursoController@obtenerLista');

$router->post('cursos', 'CursoController@agregar');
$router->get('cursos/{Id}', 'CursoController@obtenerUno');
$router->get('cursos', 'CursoController@obtenerLista');

$router->get('carreras',               'CarrerasController@obtenerTodos');
$router->get('carreras/{Id}',          'CarrerasController@obtenerUno');
$router->get('carreras/{Id}/cursos',   'CarrerasController@obtenerCursosDeCarrera');
$router->get('carreras/{Id}/previas',  'CarrerasController@obtenerPreviasEntreCursosDeCarrera');
$router->post('carreras',              'CarrerasController@agregar');

