import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import {MatCardModule} from '@angular/material/card';
import { HttpClientModule} from '@angular/common/http';
import { ReactiveFormsModule, FormsModule} from '@angular/forms';

import {MatButtonModule} from '@angular/material/button';
import {MatToolbarModule} from '@angular/material/toolbar';
import {MatIconModule} from '@angular/material/icon';
import {MatSidenavModule} from '@angular/material/sidenav';
import {MatListModule} from '@angular/material/list'; 
import {MatExpansionModule} from '@angular/material/expansion';
import {ListaCarrerasComponent } from './acceso-publico/lista-carreras/lista-carreras.component';
import { LoginComponent } from './acceso-publico/login/login.component'; 
import {MatInputModule} from '@angular/material/input';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatSnackBarModule} from '@angular/material/snack-bar'; 
import {MatSelectModule} from '@angular/material/select';
import {MatTableModule} from '@angular/material/table'; 
import {MatDatepickerModule} from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import {MatChipsModule} from '@angular/material/chips'; 

import { UsuariosComponent } from './admin/usuarios/usuarios.component';
import { RolesPipe } from './pipes/roles.pipe';
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
import { NgxGraphModule } from '@swimlane/ngx-graph';
import { ModalPreviaComponent } from './admin/carrera/carrera-abm/modal-previa/modal-previa.component';
import { RevicionInscripcionesPostulantesComponent } from './administrativo/revicion-inscripciones-postulantes/revicion-inscripciones-postulantes.component';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import { SexoPipe } from './pipes/sexo.pipe';
import { InscripcionCarreraComponent } from './acceso-publico/inscripcion-carrera/inscripcion-carrera.component';
import {MatStepperModule} from '@angular/material/stepper';
import { ModalInformarComponent } from './administrativo/revicion-inscripciones-postulantes/modal-informar/modal-informar.component';
import { VerMasComponent } from './administrativo/revicion-inscripciones-postulantes/ver-mas/ver-mas.component';
import { AsignarDocenteComponent } from './administrativo/asignar-docente/asignar-docente.component';
import { InscripcionCursoComponent } from './estudiante/inscripcion-curso/inscripcion-curso.component';
import { InscripcionExamenComponent } from './estudiante/inscripcion-examen/inscripcion-examen.component';
import { CambirContraseniaComponent } from './estudiante/cambir-contrasenia/cambir-contrasenia.component';



@NgModule({
  declarations: [
    AppComponent,
    ListaCarrerasComponent,
    LoginComponent,
    UsuariosComponent,
    RolesPipe,
    UsuarioABMComponent,
    SedesComponent,
    SedeABMComponent,
    AreaEstudioComponent,
    AreaEstudioABMComponent,
    TipoCursoComponent,
    TipoCursoABMComponent,
    CursoComponent,
    CursoABMComponent,
    AnioLectivoABMComponent,
    CarreraComponent,
    CarreraABMComponent,
    CarreraVistaComponent,
    ModalPreviaComponent,
    RevicionInscripcionesPostulantesComponent,
    SexoPipe,
    InscripcionCarreraComponent,
    ModalInformarComponent,
    VerMasComponent,
    AsignarDocenteComponent,
    InscripcionCursoComponent,
    InscripcionExamenComponent,
    CambirContraseniaComponent
  ],
  imports: [
    MatStepperModule,
    MatProgressSpinnerModule,
    NgxGraphModule,
    MatChipsModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatSelectModule,
    MatSnackBarModule,
    MatFormFieldModule,
    MatInputModule,
    MatExpansionModule,
    MatListModule,
    MatSidenavModule,
    MatIconModule,
    MatToolbarModule,
    MatButtonModule,
    MatCardModule,
    MatTableModule,
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    HttpClientModule,
    ReactiveFormsModule,
    FormsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
