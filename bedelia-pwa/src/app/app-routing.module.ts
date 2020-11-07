import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { ConsultaEscolaridadComponent } from './componentes/consulta-escolaridad/consulta-escolaridad.component';
import { InicioComponent } from './componentes/inicio/inicio.component';
import { InscripcionCursoComponent } from './componentes/inscripcion-curso/inscripcion-curso.component';
import { InscripcionExamenComponent } from './componentes/inscripcion-examen/inscripcion-examen.component';
import { LoginComponent } from './componentes/login/login.component';
import { AuthGuard } from './Guard/auth.guard';

const routes: Routes = [
  //Inico de componentes de acceso publico,
  {path: '', component: InicioComponent},
  {path: 'login', component: LoginComponent},

  {path: 'estudiante/consultar-escolaridad', component: ConsultaEscolaridadComponent,canActivate:[AuthGuard]},
  {path: 'estudiante/inscripcion/curso', component: InscripcionCursoComponent,canActivate:[AuthGuard]},
  {path: 'estudiante/inscripcion/examen', component: InscripcionExamenComponent,canActivate:[AuthGuard]},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
