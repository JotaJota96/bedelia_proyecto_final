import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';


import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ServiceWorkerModule } from '@angular/service-worker';
import { environment } from '../environments/environment';

import {MatInputModule} from '@angular/material/input';
import {MatFormFieldModule} from '@angular/material/form-field';
import {MatSnackBarModule} from '@angular/material/snack-bar'; 
import {MatSelectModule} from '@angular/material/select';
import {MatTableModule} from '@angular/material/table'; 
import {MatDatepickerModule} from '@angular/material/datepicker';
import { MatNativeDateModule } from '@angular/material/core';
import {MatChipsModule} from '@angular/material/chips'; 
import {MatSidenavModule} from '@angular/material/sidenav';
import {MatStepperModule} from '@angular/material/stepper';
import {MatProgressSpinnerModule} from '@angular/material/progress-spinner';
import {MatExpansionModule} from '@angular/material/expansion';
import {MatListModule} from '@angular/material/list'; 
import {MatIconModule} from '@angular/material/icon';
import {MatButtonModule} from '@angular/material/button';
import {MatToolbarModule} from '@angular/material/toolbar';
import {MatCardModule} from '@angular/material/card';
import { LoginComponent } from './componentes/login/login.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ConsultaEscolaridadComponent } from './componentes/consulta-escolaridad/consulta-escolaridad.component';
import { InterceptorTokenInterceptor } from './interceptores/interceptor-token.interceptor';
import { TipoActaPipe } from './pipe/tipo-acta.pipe';
import { NotaPipe } from './pipe/nota.pipe';
import { InscripcionCursoComponent } from './componentes/inscripcion-curso/inscripcion-curso.component';
import { InscripcionExamenComponent } from './componentes/inscripcion-examen/inscripcion-examen.component';
import { FechaNullPipe } from './pipe/fecha-null.pipe';
import { InicioComponent } from './componentes/inicio/inicio.component';
import { DescripcionInscripcionCursoPipe } from './pipe/descripcion-inscripcion-curso.pipe';
import { DescripcionInscripcionExamenPipe } from './pipe/descripcion-inscripcion-examen.pipe';
import { DesconectadoComponent } from './componentes/desconectado/desconectado.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    ConsultaEscolaridadComponent,
    TipoActaPipe,
    NotaPipe,
    InscripcionCursoComponent,
    InscripcionExamenComponent,
    FechaNullPipe,
    InicioComponent,
    DescripcionInscripcionExamenPipe,
    DescripcionInscripcionCursoPipe,
    DesconectadoComponent
  ],
  imports: [
    HttpClientModule,
    MatStepperModule,
    MatProgressSpinnerModule,
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

    FormsModule,
    ReactiveFormsModule,

    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    ServiceWorkerModule.register('ngsw-worker.js', { enabled: environment.production })
  ],
  providers: [
    {
      provide: HTTP_INTERCEPTORS,
      useClass: InterceptorTokenInterceptor,
      multi: true
    },
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
