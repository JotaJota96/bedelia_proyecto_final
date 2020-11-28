import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDrawer } from '@angular/material/sidenav';
import { MAT_DRAWER_CONTAINER } from '@angular/material/sidenav/drawer';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { ConnectionService } from 'ng-connection-service';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { ExamenDTO } from 'src/app/clases/examen-dto';
import { EstudianteService } from 'src/app/servis/estudiante.service';
import { ExamenService } from 'src/app/servis/examen.service';
import { UsuariosService } from 'src/app/servis/usuarios.service';

@Component({
  selector: 'app-inscripcion-examen',
  templateUrl: './inscripcion-examen.component.html',
  styleUrls: ['./inscripcion-examen.component.css']
})

export class InscripcionExamenComponent implements OnInit {
  selectedOptions: number[] = [];
  listaExamen: ExamenDTO[] = [];
  listaCarrera: CarreraDTO[] = [];
  ciEstudiante: string;
  periodoOk:boolean = undefined;

  public formulario: FormGroup;

  constructor(private onlineService: ConnectionService, private router: Router, private _snackBar: MatSnackBar, protected usuServ: UsuariosService,
    protected estudianteServis: EstudianteService, protected examenServ: ExamenService) { }

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

    this.usuServ.enPeriodo('IE').subscribe(
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

  cargarExamenes() {
    this.examenServ.getEdicionesParaInscrivirse(this.ciEstudiante, this.formulario.controls['carrera'].value).subscribe(
      (datos) => {
        datos.forEach(element => {
          if(element.habilitado == 1){
            this.listaExamen.push(element)
          }
        });
      },
      (error) => {
        this.openSnackBar("Error al obtener los exámenes para este período lectivo");
      });
  }

  confirmar() {
    if(this.selectedOptions.length == 0){
      this.openSnackBar("Debes seleccionar una carrera");
      return;
    }
    this.examenServ.inscripciones(this.ciEstudiante, this.selectedOptions).subscribe(
      (datos) => {
        this.router.navigate(['/']);
      },
      (error) => {
        this.openSnackBar("Error al inscribirse a exámenes");
      }
    );
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'center',
      verticalPosition: "bottom",
    });
  }
}
