import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

//Inico de componentes de acceso publico
import { ListaCarrerasComponent } from './acceso-publico/lista-carreras/lista-carreras.component';
import { LoginComponent } from './acceso-publico/login/login.component';
//Fin de componentes de acceso publico

//Inico de componentes de admin
import { UsuariosComponent } from './admin/usuarios/usuarios.component';
import { UsuarioABMComponent } from './admin/usuarios/usuario-abm/usuario-abm.component';
import { SedesComponent } from './admin/sedes/sedes.component';
import { SedeABMComponent } from './admin/sedes/sede-abm/sede-abm.component';
import { AreaEstudioComponent } from './admin/area-estudio/area-estudio.component';
import { AreaEstudioABMComponent } from './admin/area-estudio/area-estudio-abm/area-estudio-abm.component';
import { TipoCursoComponent } from './admin/tipo-curso/tipo-curso.component';
import { TipoCursoABMComponent } from './admin/tipo-curso/tipo-curso-abm/tipo-curso-abm.component';
import { CursoComponent } from './admin/curso/curso.component';
import { CursoABMComponent } from './admin/curso/curso-abm/curso-abm.component';
import { AnioLectivoABMComponent } from './admin/anio-lectivo-abm/anio-lectivo-abm.component';
import { CarreraComponent } from './admin/carrera/carrera.component';
import { CarreraABMComponent } from './admin/carrera/carrera-abm/carrera-abm.component';
import { CarreraVistaComponent } from './acceso-publico/carrera-vista/carrera-vista.component';
import { RevicionInscripcionesPostulantesComponent } from './administrativo/revicion-inscripciones-postulantes/revicion-inscripciones-postulantes.component';
import { InscripcionCarreraComponent } from './acceso-publico/inscripcion-carrera/inscripcion-carrera.component';
import { VerMasComponent } from './administrativo/revicion-inscripciones-postulantes/ver-mas/ver-mas.component';
import { AsignarDocenteComponent } from './administrativo/asignar-docente/asignar-docente.component';
import { InscripcionExamenComponent } from './estudiante/inscripcion-examen/inscripcion-examen.component';
import { InscripcionCursoComponent } from './estudiante/inscripcion-curso/inscripcion-curso.component';
import { CambirContraseniaComponent } from './estudiante/cambir-contrasenia/cambir-contrasenia.component';
import { ControlAsistenciaComponent } from './docente/control-asistencia/control-asistencia.component';
import { IngresarResultadoCursoComponent } from './docente/ingresar-resultado-curso/ingresar-resultado-curso.component';
import { IngresarResultadoExamenComponent } from './docente/ingresar-resultado-examen/ingresar-resultado-examen.component';
import { ConsultaEscolaridadComponent } from './estudiante/consulta-escolaridad/consulta-escolaridad.component';
//Fin de componentes de admin

//Inico de componentes de administrativo
//...
//Fin de componentes de administrativo

//Inico de componentes de estudiante
//...
//Fin de componentes de estudiante

//Inico de componentes de docente
//...
//Fin de componentes de docente


const routes: Routes = [
  //Inico de componentes de acceso publico
  {path: '', component: ListaCarrerasComponent},
  
  {path: 'login', component: LoginComponent},
  
  {path: 'ver/carrera/:id', component: CarreraVistaComponent},

  {path: 'inscripcion/carrera/:id', component: InscripcionCarreraComponent},
  //Fin de componentes de acceso publico

  //Inico de componentes de admin
  {path: 'admin/usuarios', component: UsuariosComponent},
  {path: 'admin/usuarios/abm', component: UsuarioABMComponent},
  {path: 'admin/usuarios/abm/:id', component: UsuarioABMComponent},
  
  {path: 'admin/sede', component: SedesComponent},
  {path: 'admin/sede/abm', component: SedeABMComponent},
  {path: 'admin/sede/abm/:id', component: SedeABMComponent},
  
  {path: 'admin/area', component: AreaEstudioComponent},
  {path: 'admin/area/abm', component: AreaEstudioABMComponent},
  
  {path: 'admin/tipo', component: TipoCursoComponent},
  {path: 'admin/tipo/abm', component: TipoCursoABMComponent},
  
  {path: 'admin/curso', component: CursoComponent},
  {path: 'admin/curso/abm', component: CursoABMComponent},
  {path: 'admin/curso/abm/:id', component: CursoABMComponent},
  
  {path: 'admin/periodos', component: AnioLectivoABMComponent},

  {path: 'admin/carrera', component: CarreraComponent},
  {path: 'admin/carrera/abm', component: CarreraABMComponent},
  
  //Fin de componentes de admin

  //Inico de componentes de administrativo
  {path: 'administrativo/revicion-postulante', component: RevicionInscripcionesPostulantesComponent},
  
  {path: 'administrativo/postulante/ver/:id', component: VerMasComponent},
  
  {path: 'administrativo/asignar-docente', component: AsignarDocenteComponent},
  //Fin de componentes de administrativo

  //Inico de componentes de estudiante
  {path: 'estudiante/inscripcion/curso', component: InscripcionCursoComponent},
  
  {path: 'estudiante/inscripcion/examen', component: InscripcionExamenComponent},

  {path: 'estudiante/cambiar/contrasenia', component: CambirContraseniaComponent},

  {path: 'estudiante/consultar-escolaridad', component: ConsultaEscolaridadComponent},
  //Fin de componentes de estudiante

  //Inico de componentes de docente
  {path: 'docente/control-asistencia', component: ControlAsistenciaComponent},
  
  {path: 'docente/ingreso/resultados-curso', component: IngresarResultadoCursoComponent},
  {path: 'docente/ingreso/resultados-examen', component: IngresarResultadoExamenComponent},
  //Fin de componentes de docente

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
