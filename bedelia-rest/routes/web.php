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


$router->post('usuarios/login',    ['middleware' => [], 'uses' => 'UsuarioController@login']);
$router->put('usuarios/passReset', ['middleware' => ['auth'],  'uses' => 'UsuarioController@cambiarContrasenia']);
$router->get('usuarios/docentes',  ['middleware' => ['auth'],  'uses' => 'UsuarioController@obtenerDocentes']);
$router->get('usuarios/{ci}',      ['middleware' => ['auth'],  'uses' => 'UsuarioController@obtenerUno']);
$router->get('usuarios',           ['middleware' => ['auth'],  'uses' => 'UsuarioController@obtenerTodos']);
$router->post('usuarios',          ['middleware' => ['auth'],  'uses' => 'UsuarioController@agregar']);

$router->get( 'sedes/{id}',                ['middleware' => ['auth'],  'uses' => 'SedesController@obtenerUno']);
$router->get( 'sedes',                     ['middleware' => ['auth'],  'uses' => 'SedesController@obtenerTodos']);
$router->post('sedes',                     ['middleware' => ['auth'],  'uses' => 'SedesController@agregar']);
$router->get('sedes/{id}/postulantes',     ['middleware' => ['auth'],  'uses' => 'SedesController@obtenerListaPostulantesDeSede']);
$router->get('sedes/{id}/edicionesCurso',  ['middleware' => ['auth'],  'uses' => 'SedesController@obtenerEdicionesCurso']);
$router->get('sedes/{id}/examenes',        ['middleware' => ['auth'],  'uses' => 'SedesController@obtenerExamenes']);
$router->get('sedes/{id}/actas',           ['middleware' => ['auth'],  'uses' => 'SedesController@obtenerActas']);

$router->post('areasEstudio',              ['middleware' => ['auth'],  'uses' => 'AreaEstudioController@agregar']);
$router->get('areasEstudio/{Id}',          ['middleware' => ['auth'],  'uses' => 'AreaEstudioController@obtenerUno']);
$router->get('areasEstudio',               ['middleware' => ['auth'],  'uses' => 'AreaEstudioController@obtenerLista']);
$router->get('areasEstudio/{Id}/cursos',   ['middleware' => ['auth'],  'uses' => 'AreaEstudioController@obtenerCursosPertenecientesAUnArea']);

$router->post('tiposCurso',       ['middleware' => ['auth'],  'uses' => 'TipoCursoController@agregar']);
$router->get('tiposCurso/{Id}',   ['middleware' => ['auth'],  'uses' => 'TipoCursoController@obtenerUno']);
$router->get('tiposCurso',        ['middleware' => ['auth'],  'uses' => 'TipoCursoController@obtenerLista']);

$router->post('cursos',      ['middleware' => ['auth'],  'uses' => 'CursoController@agregar']);
$router->get('cursos/{Id}',  ['middleware' => ['auth'],  'uses' => 'CursoController@obtenerUno']);
$router->get('cursos',       ['middleware' => ['auth'],  'uses' => 'CursoController@obtenerLista']);

$router->get('carreras',               ['middleware' => [],        'uses' => 'CarrerasController@obtenerTodos']);
$router->get('carreras/{Id}',          ['middleware' => [],        'uses' => 'CarrerasController@obtenerUno']);
$router->get('carreras/{Id}/cursos',   ['middleware' => [],        'uses' => 'CarrerasController@obtenerCursosDeCarrera']);
$router->get('carreras/{Id}/previas',  ['middleware' => [],        'uses' => 'CarrerasController@obtenerPreviasEntreCursosDeCarrera']);
$router->post('carreras',              ['middleware' => ['auth'],  'uses' => 'CarrerasController@agregar']);

$router->post('periodos',              ['middleware' => ['auth'],  'uses' => 'PeriodoLectivoController@agregar']);
$router->get('periodos/actual/{tipo}', ['middleware' => [],        'uses' => 'PeriodoLectivoController@actual']);
$router->get('periodos',               ['middleware' => [],        'uses' => 'PeriodoLectivoController@obtenerLista']);

$router->get('postulantes/{id}',            ['middleware' => ['auth'],  'uses' => 'PostulantesController@obtenerUno']);
$router->post('postulantes',                ['middleware' => [],        'uses' => 'PostulantesController@agregar']);
$router->delete('postulantes/{id}',         ['middleware' => ['auth'],  'uses' => 'PostulantesController@rechazar']);
$router->post('postulantes/{id}/notificar', ['middleware' => ['auth'],  'uses' => 'PostulantesController@notificar']);
$router->post('postulantes/{id}/aceptar',   ['middleware' => ['auth'],  'uses' => 'PostulantesController@aceptar']);

$router->get('administrativos/{ci}/sede',    ['middleware' => ['auth'],  'uses' => 'AdministrativosController@obtenerSede']);
$router->post('administrativos/{ci}/sede',   ['middleware' => ['auth'],  'uses' => 'AdministrativosController@establecerSede']);

$router->post('edicionesCurso/inscripciones/{ciEstudiante}',    ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@asignarEstudiante']);
$router->post('edicionesCurso/{id}/clasesDictada',              ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@Agregar']);
$router->get('edicionesCurso/{id}/notas',                       ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@ObtenerNotas']);
$router->post('edicionesCurso/{id}/notas',                      ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@IngresarNotas']);
$router->put('edicionesCurso/{id}/notas',                       ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@ConfirmarActa']);
$router->put('edicionesCurso/{id}/docente/{ciDocente}',         ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@asignarDocente']);
$router->get('edicionesCurso/docente/{ciDocente}',              ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@CursosDocente']);
$router->get('edicionesCurso/{id}/estudiantes',                 ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@estudiantes']);
$router->get('edicionesCurso/{ciEstudiante}/{idCarrera}',       ['middleware' => ['auth'],  'uses' => 'EdicionesCursoController@listarParaInscripcion']);

$router->post('examenes/inscripciones/{ciEstudiante}',    ['middleware' => ['auth'],  'uses' => 'ExamenController@asignarEstudiante']);
$router->get('examenes/docente/{ciDocente}',              ['middleware' => ['auth'],  'uses' => 'ExamenController@ExamenessDocente']);
$router->put('examenes/{id}/docente/{ciDocente}',         ['middleware' => ['auth'],  'uses' => 'ExamenController@asignarDocente']);
$router->get('examenes/{id}/notas',                       ['middleware' => ['auth'],  'uses' => 'ExamenController@ObtenerNotas']);
$router->post('examenes/{id}/notas',                      ['middleware' => ['auth'],  'uses' => 'ExamenController@IngresarNotas']);
$router->put('examenes/{id}/notas',                       ['middleware' => ['auth'],  'uses' => 'ExamenController@ConfirmarActa']);
$router->get('examenes/{ciEstudiante}/{idCarrera}',       ['middleware' => ['auth'],  'uses' => 'ExamenController@listarParaInscripcion']);

$router->get('estudiantes/escolaridad/{codigo}',                       ['middleware' => [],        'uses' => 'EstudianteController@verificarEscolaridad']);
$router->get('estudiantes/escolaridad/{codigo}/existe',                ['middleware' => [],        'uses' => 'EstudianteController@verificarCodigoEscolaridad']);
$router->get('estudiantes/{ciEstudiante}/escolaridad/{idCarrera}',     ['middleware' => ['auth'],  'uses' => 'EstudianteController@obtenerEscolaridad']);
$router->get('estudiantes/{ciEstudiante}/escolaridad/{idCarrera}/pdf', ['middleware' => ['auth'],  'uses' => 'EstudianteController@obtenerEscolaridadPDF']);
$router->get('estudiantes/{ciEstudiante}/carreras',                    ['middleware' => ['auth'],  'uses' => 'EstudianteController@Careras']);
$router->put('estudiantes/{ciEstudiante}/asistencias',                 ['middleware' => ['auth'],  'uses' => 'EstudianteController@JustificarInasistencias']);


