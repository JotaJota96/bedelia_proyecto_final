import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ListaCarrerasComponent } from './acceso-publico/lista-carreras/lista-carreras.component';
import { LoginComponent } from './acceso-publico/login/login.component';
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
import { CambiarContraseniaComponent } from './usuarios/cambiar-contrasenia/cambiar-contrasenia.component';
import { ControlAsistenciaComponent } from './docente/control-asistencia/control-asistencia.component';
import { IngresarResultadoCursoComponent } from './docente/ingresar-resultado-curso/ingresar-resultado-curso.component';
import { IngresarResultadoExamenComponent } from './docente/ingresar-resultado-examen/ingresar-resultado-examen.component';
import { ConsultaEscolaridadComponent } from './estudiante/consulta-escolaridad/consulta-escolaridad.component';
import { JustificarInasistenciaComponent } from './administrativo/justificar-inasistencia/justificar-inasistencia.component';
import { RevicionActaComponent } from './administrativo/revicion-acta/revicion-acta.component';
import { VerificacionEscolaridadComponent } from './acceso-publico/verificacion-escolaridad/verificacion-escolaridad.component';

import { AuthGuard } from './guards/auth.guard';
import { EstudianteService } from './servicios/estudiante.service';
import { EstudianteGuard } from './guards/estudiante.guard';
import { AdminGuard } from './guards/admin.guard';
import { AdministrativoGuard } from './guards/administrativo.guard';
import { DocenteGuard } from './guards/docente.guard';
import { LoginGuard } from './guards/login.guard';


const routes: Routes = [
  //Inico de componentes de acceso publico
  {path: '', component: ListaCarrerasComponent},
  
  {path: 'login', component: LoginComponent, canActivate:[LoginGuard]},
  
  {path: 'ver/carrera/:id', component: CarreraVistaComponent},

  {path: 'inscripcion/carrera/:id', component: InscripcionCarreraComponent},

  {path: 'verificarEscolaridad', component: VerificacionEscolaridadComponent},
  //Fin de componentes de acceso publico

  //Inico de componentes de usuarios en general
  {path: 'usuarios/cambiar/contrasenia', component: CambiarContraseniaComponent, canActivate:[AuthGuard]},
  //Fin de componentes de usuarios en general

  //Inico de componentes de admin
  {path: 'admin/usuarios', component: UsuariosComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/usuarios/abm', component: UsuarioABMComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/usuarios/abm/:id', component: UsuarioABMComponent, canActivate:[AuthGuard,AdminGuard]},
  
  {path: 'admin/sede', component: SedesComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/sede/abm', component: SedeABMComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/sede/abm/:id', component: SedeABMComponent, canActivate:[AuthGuard,AdminGuard]},
  
  {path: 'admin/area', component: AreaEstudioComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/area/abm', component: AreaEstudioABMComponent, canActivate:[AuthGuard,AdminGuard]},
  
  {path: 'admin/tipo', component: TipoCursoComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/tipo/abm', component: TipoCursoABMComponent, canActivate:[AuthGuard,AdminGuard]},
  
  {path: 'admin/curso', component: CursoComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/curso/abm', component: CursoABMComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/curso/abm/:id', component: CursoABMComponent, canActivate:[AuthGuard,AdminGuard]},
  
  {path: 'admin/periodos', component: AnioLectivoABMComponent, canActivate:[AuthGuard,AdminGuard]},

  {path: 'admin/carrera', component: CarreraComponent, canActivate:[AuthGuard,AdminGuard]},
  {path: 'admin/carrera/abm', component: CarreraABMComponent, canActivate:[AuthGuard,AdminGuard]},
  //Fin de componentes de admin

  //Inico de componentes de administrativo
  {path: 'administrativo/revicion-postulante', component: RevicionInscripcionesPostulantesComponent, canActivate:[AuthGuard,AdministrativoGuard]},
  
  {path: 'administrativo/postulante/ver/:id', component: VerMasComponent, canActivate:[AuthGuard,AdministrativoGuard]},
  
  {path: 'administrativo/asignar-docente', component: AsignarDocenteComponent, canActivate:[AuthGuard,AdministrativoGuard]},

  {path: 'administrativo/justificar-inasistencia', component: JustificarInasistenciaComponent, canActivate:[AuthGuard,AdministrativoGuard]},

  {path: 'administrativo/revicion-acta', component: RevicionActaComponent, canActivate:[AuthGuard,AdministrativoGuard]},
  //Fin de componentes de administrativo

  //Inico de componentes de estudiante
  {path: 'estudiante/inscripcion/curso', component: InscripcionCursoComponent, canActivate:[AuthGuard,EstudianteGuard]},
  
  {path: 'estudiante/inscripcion/examen', component: InscripcionExamenComponent, canActivate:[AuthGuard,EstudianteGuard]},

  {path: 'estudiante/consultar-escolaridad', component: ConsultaEscolaridadComponent, canActivate:[AuthGuard,EstudianteGuard]},
  //Fin de componentes de estudiante

  //Inico de componentes de docente
  {path: 'docente/control-asistencia', component: ControlAsistenciaComponent, canActivate:[AuthGuard,DocenteGuard]},
  
  {path: 'docente/ingreso/resultados-curso', component: IngresarResultadoCursoComponent, canActivate:[AuthGuard,DocenteGuard]},
  {path: 'docente/ingreso/resultados-examen', component: IngresarResultadoExamenComponent, canActivate:[AuthGuard,DocenteGuard]},
  //Fin de componentes de docente

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
