import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { ExamenDTO } from 'src/app/clases/examen-dto';
import { CursoService } from 'src/app/servicios/curso.service';
import { EstudianteService } from 'src/app/servicios/estudiante.service';
import { ExamenesService } from 'src/app/servicios/examenes.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-inscripcion-examen',
  templateUrl: './inscripcion-examen.component.html',
  styleUrls: ['./inscripcion-examen.component.css']
})
export class InscripcionExamenComponent implements OnInit {
  selectedOptions: CursoDTO[] = [];
  listaExamen: ExamenDTO[] = [];
  listaCarrera: CarreraDTO[] = [];
  ciEstudiante: string;


  public formulario: FormGroup;

  constructor(private _snackBar: MatSnackBar, protected usuServ: UsuariosService, 
    protected estudianteServis: EstudianteService , protected examenServ: ExamenesService) { }

  ngOnInit(): void {
    this.ciEstudiante = this.usuServ.obtenerDatosLoginAlmacenado().cedula;

    this.estudianteServis.getCarreras(this.ciEstudiante).subscribe(
      (datos) => {
        this.listaCarrera = datos;
      },
      (error) => {
        this.openSnackBar("Error al cargar las carreras del estudiante");
      }
    );
    
    this.formulario = new FormGroup({
      carrera: new FormControl('', [Validators.required])
    });
  }

  cargarMateria(){
    this.examenServ.getEdicionesParaInscrivirse(this.ciEstudiante,this.formulario.controls['carrera'].value).subscribe(
      (datos)=>{
        this.listaExamen = datos;
      },
      (error)=>{
        this.openSnackBar("Error al obtener los examenes para la carrera seleccionada");
      });
  }

  confirmar(){
    console.log(this.selectedOptions)
    let usu = this.usuServ.obtenerDatosLoginAlmacenado();
    this.selectedOptions.forEach(element => {
      this.examenServ.inscripciones(element.id, usu.cedula).subscribe(
        (datos)=>{
  
        },
        (error)=>{
  
        }
      );
    });
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
