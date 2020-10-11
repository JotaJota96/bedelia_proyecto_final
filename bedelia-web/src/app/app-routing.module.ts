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

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
