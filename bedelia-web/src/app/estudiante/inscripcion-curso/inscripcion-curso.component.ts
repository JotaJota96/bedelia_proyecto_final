import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { EdicionCursoDTO } from 'src/app/clases/edicion-curso-dto';
import { CarreraService } from 'src/app/servicios/carrera.service';
import { EdicionesCursoService } from 'src/app/servicios/ediciones-curso.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-inscripcion-curso',
  templateUrl: './inscripcion-curso.component.html',
  styleUrls: ['./inscripcion-curso.component.css']
})
export class InscripcionCursoComponent implements OnInit {
  selectedOptions: EdicionCursoDTO[] = [];
  listaCurso: EdicionCursoDTO[] = [];
  listaCarrera: CarreraDTO[] = [];
  ciEstudiante: string;


  public formulario: FormGroup;
  constructor(private _snackBar: MatSnackBar, protected usuServ: UsuariosService,
    protected carreraServis: CarreraService, protected edicionCursoServ: EdicionesCursoService) { }

  ngOnInit(): void {
    this.ciEstudiante = this.usuServ.obtenerDatosLoginAlmacenado().cedula;

    this.carreraServis.getAll().subscribe(
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

  cargarMateria() {
    this.edicionCursoServ.getEdicionesParaInscrivirse(this.ciEstudiante, this.formulario.controls['carrera'].value).subscribe(
      (datos) => {
        this.listaCurso = datos;
      },
      (error) => {
        this.openSnackBar("Error al obtener los cursos para la carrera seleccionada")
      }
    );
  }

  confirmar() {
    console.log(this.selectedOptions)
    let usu = this.usuServ.obtenerDatosLoginAlmacenado();
    this.selectedOptions.forEach(element => {
      this.edicionCursoServ.inscripciones(element.id, usu.cedula).subscribe(
        (error) => {
          this.openSnackBar("No se pudo inscrivir al curso: " + element.curso.nombre);
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
