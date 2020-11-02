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
    // redirige a la documentacion de Swagger
    return redirect()->to('api/documentation');
});
// este es para probar los correos cuando se estan haciendo
$router->post('correos',         'CorreosController@enviarCorreo');

$router->post('usuarios/login',   'UsuarioController@login');
$router->put('usuarios/passReset', 'UsuarioController@cambiarContrasenia');
$router->get('usuarios/docentes', 'UsuarioController@obtenerDocentes');
$router->get('usuarios/{ci}',     'UsuarioController@obtenerUno');
$router->get('usuarios',          'UsuarioController@obtenerTodos');
$router->post('usuarios',         'UsuarioController@agregar');

$router->get( 'sedes/{id}',                'SedesController@obtenerUno');
$router->get( 'sedes',                     'SedesController@obtenerTodos');
$router->post('sedes',                     'SedesController@agregar');
$router->get('sedes/{id}/postulantes',     'SedesController@obtenerListaPostulantesDeSede');
$router->get('sedes/{id}/edicionesCurso',  'SedesController@obtenerEdicionesCurso');
$router->get('sedes/{id}/examenes',        'SedesController@obtenerExamenes');
$router->get('sedes/{id}/actas',           'SedesController@obtenerActas');

$router->post('areasEstudio',              'AreaEstudioController@agregar');
$router->get('areasEstudio/{Id}',          'AreaEstudioController@obtenerUno');
$router->get('areasEstudio',               'AreaEstudioController@obtenerLista');
$router->get('areasEstudio/{Id}/cursos',   'AreaEstudioController@obtenerCursosPertenecientesAUnArea');

$router->post('tiposCurso',       'TipoCursoController@agregar');
$router->get('tiposCurso/{Id}',   'TipoCursoController@obtenerUno');
$router->get('tiposCurso',        'TipoCursoController@obtenerLista');

$router->post('cursos',      'CursoController@agregar');
$router->get('cursos/{Id}',  'CursoController@obtenerUno');
$router->get('cursos',       'CursoController@obtenerLista');

$router->get('carreras',               'CarrerasController@obtenerTodos');
$router->get('carreras/{Id}',          'CarrerasController@obtenerUno');
$router->get('carreras/{Id}/cursos',   'CarrerasController@obtenerCursosDeCarrera');
$router->get('carreras/{Id}/previas',  'CarrerasController@obtenerPreviasEntreCursosDeCarrera');
$router->post('carreras',              'CarrerasController@agregar');

$router->post('periodos',   'PeriodoLectivoController@agregar');
$router->get('periodos',    'PeriodoLectivoController@obtenerLista');

$router->get('postulantes/{id}',            'PostulantesController@obtenerUno');
$router->post('postulantes',                'PostulantesController@agregar');
$router->delete('postulantes/{id}',         'PostulantesController@rechazar');
$router->post('postulantes/{id}/notificar', 'PostulantesController@notificar');
$router->post('postulantes/{id}/aceptar',   'PostulantesController@aceptar');

$router->get('administrativos/{ci}/sede',    'AdministrativosController@obtenerSede');
$router->post('administrativos/{ci}/sede',   'AdministrativosController@establecerSede');

$router->post('edicionesCurso/inscripciones/{ciEstudiante}',    'EdicionesCursoController@asignarEstudiante');
$router->post('edicionesCurso/{id}/clasesDictada',              'EdicionesCursoController@Agregar');
$router->get('edicionesCurso/{id}/notas',                       'EdicionesCursoController@ObtenerNotas');
$router->post('edicionesCurso/{id}/notas',                      'EdicionesCursoController@IngresarNotas');
$router->put('edicionesCurso/{id}/notas',                       'EdicionesCursoController@ConfirmarActa');
$router->put('edicionesCurso/{id}/docente/{ciDocente}',         'EdicionesCursoController@asignarDocente');
$router->get('edicionesCurso/docente/{ciDocente}',              'EdicionesCursoController@CursosDocente');
$router->get('edicionesCurso/{id}/estudiantes',                 'EdicionesCursoController@estudiantes');
$router->get('edicionesCurso/{ciEstudiante}/{idCarrera}',       'EdicionesCursoController@listarParaInscripcion');

$router->post('examenes/inscripciones/{ciEstudiante}',    'ExamenController@asignarEstudiante');
$router->get('examenes/docente/{ciDocente}',              'ExamenController@ExamenessDocente');
$router->put('examenes/{id}/docente/{ciDocente}',         'ExamenController@asignarDocente');
$router->get('examenes/{id}/notas',                       'ExamenController@ObtenerNotas');
$router->post('examenes/{id}/notas',                      'ExamenController@IngresarNotas');
$router->put('examenes/{id}/notas',                       'ExamenController@ConfirmarActa');
$router->get('examenes/{ciEstudiante}/{idCarrera}',       'ExamenController@listarParaInscripcion');

$router->get('estudiantes/escolaridad/{codigo}',                       'EstudianteController@verificarEscolaridad');
$router->get('estudiantes/escolaridad/{codigo}/existe',                'EstudianteController@verificarCodigoEscolaridad');
$router->get('estudiantes/{ciEstudiante}/escolaridad/{idCarrera}',     'EstudianteController@obtenerEscolaridad');
$router->get('estudiantes/{ciEstudiante}/escolaridad/{idCarrera}/pdf', 'EstudianteController@obtenerEscolaridadPDF');
$router->get('estudiantes/{ciEstudiante}/carreras',                    'EstudianteController@Careras');
$router->put('estudiantes/{ciEstudiante}/asistencias',                 'EstudianteController@JustificarInasistencias');


