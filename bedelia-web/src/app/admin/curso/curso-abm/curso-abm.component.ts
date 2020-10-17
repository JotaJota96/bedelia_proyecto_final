import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { ActivatedRoute, Router } from '@angular/router';
import { AreaEstudioDTO } from 'src/app/clases/area-estudio-dto';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { TipoCursoDTO } from 'src/app/clases/tipo-curso-dto';
import { AreaEstudioService } from 'src/app/servicios/area-estudio.service';
import { CursoService } from 'src/app/servicios/curso.service';
import { TipoCursoService } from 'src/app/servicios/tipo-curso.service';

@Component({
  selector: 'app-curso-abm',
  templateUrl: './curso-abm.component.html',
  styleUrls: ['./curso-abm.component.css']
})
export class CursoABMComponent implements OnInit {
  soloLectura: boolean = false;
  listaArea: AreaEstudioDTO[];
  listaTipo: TipoCursoDTO[];

  public formulario: FormGroup;

  constructor(private _snackBar: MatSnackBar, protected cursoServ: CursoService, protected areaServ: AreaEstudioService, protected tipoServ: TipoCursoService,
    private router: Router, private rutaActiva: ActivatedRoute) { }

  ngOnInit(): void {

    this.areaServ.getAll().subscribe(
      (datos) => {
        this.listaArea = datos;
      },
      (error) => {
        this.openSnackBar("No se pudieron cargar las areas de estudio de la base de dato");
      }
    )

    this.tipoServ.getAll().subscribe(
      (datos) => {
        this.listaTipo = datos;
      },
      (error) => {
        alert("Error");
      }
    )


    let parametrosId: number = this.rutaActiva.snapshot.params.id;

    if (parametrosId != undefined) {
      this.soloLectura = true
      let curso = new CursoDTO();
      this.cursoServ.get(parametrosId).subscribe(
        (datos) => {
          this.cargaDeDatos(datos);
        },
        (error) => {
          alert("Error");
        }
      );
    }

    this.formulario = new FormGroup({

      // curso
      nombre: new FormControl('', [Validators.required]),
      descripcion: new FormControl('', [Validators.required]),
      max_inasistencias: new FormControl('', [Validators.required]),
      cant_creditos: new FormControl('', [Validators.required]),
      cant_clases: new FormControl('', [Validators.required]),

      // areaEstudio / tipoCurso
      area_estudio: new FormControl('', [Validators.required]),
      tipo_curso: new FormControl('', [Validators.required])
    });
  }

  cargaDeDatos(curso: CursoDTO) {
    // curso
    this.formulario.controls['nombre'].setValue(curso.nombre);
    this.formulario.controls['descripcion'].setValue(curso.descripcion);
    this.formulario.controls['max_inasistencias'].setValue(curso.max_inasistencias);
    this.formulario.controls['cant_creditos'].setValue(curso.cant_creditos);
    this.formulario.controls['cant_clases'].setValue(curso.cant_clases);
    // areaEstudio / tipoCurso
    this.formulario.controls['area_estudio'].setValue(curso.area_estudio.id);
    this.formulario.controls['tipo_curso'].setValue(curso.tipo_curso.id);

  }

  vaciarDatos() {
    // curso
    this.formulario.controls['nombre'].setValue("");
    this.formulario.controls['descripcion'].setValue("");
    this.formulario.controls['max_inasistencias'].setValue("");
    this.formulario.controls['cant_creditos'].setValue("");
    this.formulario.controls['cant_clases'].setValue("");
    // areaEstudio / tipoCurso
    this.formulario.controls['area_estudio'].setValue("");
    this.formulario.controls['tipo_curso'].setValue("");
  }

  agregar() {
    let sede: CursoDTO = new CursoDTO();

    sede.id = 0;
    sede.nombre = this.formulario.controls['nombre'].value;
    sede.descripcion = this.formulario.controls['descripcion'].value;
    sede.max_inasistencias = this.formulario.controls['max_inasistencias'].value;
    sede.cant_creditos = this.formulario.controls['cant_creditos'].value;
    sede.cant_clases = this.formulario.controls['cant_clases'].value;

    let idArea = this.formulario.controls['area_estudio'].value;
    let idTipo = this.formulario.controls['tipo_curso'].value;

    this.listaArea.forEach(element => {
      if(element.id == idArea){
        sede.area_estudio = element;
      }
    });

    this.listaTipo.forEach(element => {
      if(element.id == idArea){
        sede.tipo_curso = element;
      }
    });

    this.cursoServ.create(sede).subscribe(
      (datos) => {
        this.router.navigate(['/admin/curso']);
      },
      (error) => {
        this.openSnackBar("No se pudo crear el curso");
      }
    );
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
