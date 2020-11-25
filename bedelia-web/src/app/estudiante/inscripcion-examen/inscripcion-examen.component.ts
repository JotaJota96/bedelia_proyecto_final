import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { ExamenDTO } from 'src/app/clases/examen-dto';
import { openSnackBar } from 'src/app/global-functions';
import { AnioLectivoService } from 'src/app/servicios/anio-lectivo.service';
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
  selectedOptions: number[] = [];
  listaExamen: ExamenDTO[] = [];
  listaCarrera: CarreraDTO[] = [];
  ciEstudiante: string;
  periodoOk:boolean = undefined;


  public formulario: FormGroup;

  constructor(private router: Router, private _snackBar: MatSnackBar, protected usuServ: UsuariosService,
    protected estudianteServis: EstudianteService, protected examenServ: ExamenesService,
    protected alecServ:AnioLectivoService) { }

  ngOnInit(): void {
    this.alecServ.enPeriodo('IE').subscribe(
      (data) => { this.periodoOk = true; },
      (error) => { this.periodoOk = false; }
    );

    this.ciEstudiante = this.usuServ.obtenerDatosLoginAlmacenado().cedula;

    this.estudianteServis.getCarreras(this.ciEstudiante).subscribe(
      (datos) => {
        this.listaCarrera = datos;
      },
      (error) => {
        openSnackBar(this._snackBar, "Error al cargar las carreras");
      }
    );

    this.formulario = new FormGroup({
      carrera: new FormControl('', [Validators.required])
    });
  }

  cargarExamenes() {
    this.examenServ.getEdicionesParaInscribirse(this.ciEstudiante, this.formulario.controls['carrera'].value).subscribe(
      (datos) => {
        this.listaExamen = datos;
      },
      (error) => {
        openSnackBar(this._snackBar, "Error al obtener los exámenes para este periodo lectivo");
      });
  }

  confirmar() {
    this.examenServ.inscripciones(this.ciEstudiante, this.selectedOptions).subscribe(
      (datos) => {
        this.router.navigate(['/']);
      },
      (error) => {
        openSnackBar(this._snackBar, "Error al inscribirse a una examen");
      }
    );
  }

}
