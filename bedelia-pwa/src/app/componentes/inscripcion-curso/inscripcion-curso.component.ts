import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { ConnectionService } from 'ng-connection-service';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { EdicionCursoDTO } from 'src/app/clases/edicion-curso-dto';
import { EdicionesCursoService } from 'src/app/servis/ediciones-curso.service';
import { EstudianteService } from 'src/app/servis/estudiante.service';
import { UsuariosService } from 'src/app/servis/usuarios.service';

@Component({
  selector: 'app-inscripcion-curso',
  templateUrl: './inscripcion-curso.component.html',
  styleUrls: ['./inscripcion-curso.component.css']
})
export class InscripcionCursoComponent implements OnInit {
  selectedOptions: number[] = [];
  listaCurso: EdicionCursoDTO[] = [];
  listaCarrera: CarreraDTO[] = [];
  ciEstudiante: string;
  periodoOk:boolean = undefined;

  listaVacia:boolean = undefined;
  mostrarSpinner:boolean = false;

  public formulario: FormGroup;
  
  constructor(private onlineService:ConnectionService ,private router:Router, private _snackBar: MatSnackBar, protected usuServ: UsuariosService,
    protected estudianteServis: EstudianteService, protected edicionCursoServ: EdicionesCursoService) { }

  ngOnInit(): void {

    if(!navigator.onLine){
      this.router.navigate(['/desconectado']);
      return;
    }
    this.onlineService.monitor().subscribe(
      (conectado)=>{
        if(!conectado){
          this.router.navigate(['/desconectado']);
          return;
        }
      }
    );

    this.usuServ.enPeriodo('IC').subscribe(
      (data) => { this.periodoOk = true; },
      (error) => { this.periodoOk = false; }
    );
    this.ciEstudiante = this.usuServ.obtenerDatosLoginAlmacenado().cedula;

    this.estudianteServis.getCarreras(this.ciEstudiante).subscribe(
      (datos) => {
        this.listaCarrera = datos;
      },
      (error) => {
        this.openSnackBar("Error al cargar las carreras");
      }
    );

    this.formulario = new FormGroup({
      carrera: new FormControl('', [Validators.required])
    });
  }

  cargarMateria() {
    this.listaVacia = undefined;
    this.mostrarSpinner = true;
    this.edicionCursoServ.getEdicionesParaInscrivirse(this.ciEstudiante, this.formulario.controls['carrera'].value).subscribe(
      (datos) => {
        this.listaCurso = datos.filter(element => element.habilitado == 1);
        this.listaVacia = this.listaCurso.length == 0;
        this.mostrarSpinner = false;
      },
      (error) => {
        this.mostrarSpinner = false;
        this.openSnackBar("Error al obtener los cursos para la carrera seleccionada");
      }
    );
  }

  confirmar() {
    if(this.selectedOptions.length == 0){
      this.openSnackBar("Debes seleccionar una carrera");
      return;
    }
    let usu = this.usuServ.obtenerDatosLoginAlmacenado();
    this.edicionCursoServ.inscripciones(this.ciEstudiante, this.selectedOptions).subscribe(
      (datos) => {
        this.router.navigate(['/']);
      },
      (error)=>{
        this.openSnackBar("Error al inscribirse a cursos");
      }
    );
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 4000,
      horizontalPosition: 'center',
      verticalPosition: "bottom",
    });
  }
}
