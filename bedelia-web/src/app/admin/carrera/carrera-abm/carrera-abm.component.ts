import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { AreaEstudioDTO } from 'src/app/clases/area-estudio-dto';
import { CarreraCreateDTO } from 'src/app/clases/carrera-create-dto';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { PreviaDTO } from 'src/app/clases/previa-dto';
import { SedeDTO } from 'src/app/clases/sede-dto';
import { AreaEstudioService } from 'src/app/servicios/area-estudio.service';
import { CarreraService } from 'src/app/servicios/carrera.service';
import { CursoService } from 'src/app/servicios/curso.service';
import { SedesService } from 'src/app/servicios/sedes.service';
import { ModalPreviaComponent } from './modal-previa/modal-previa.component';

export interface Isemestre {
  clave: number;
  cursos: CursoDTO[];
}

@Component({
  selector: 'app-carrera-abm',
  templateUrl: './carrera-abm.component.html',
  styleUrls: ['./carrera-abm.component.css']
})
export class CarreraABMComponent implements OnInit {

  listaSedeSeleccionada: SedeDTO[] = [];
  listaAreaSeleccionada: AreaEstudioDTO[] = [];
  listaTodosCursoSeleccionados: CursoDTO[] = [];
  listaPrevias: PreviaDTO[] = []
  contadorSemestres: number = 1;

  listaCursoSeleccionados: CursoDTO[] = [];
  listaSedes: SedeDTO[] = [];
  listaCursoPrevias: CursoDTO[] = [];
  listaAreasEstudio: AreaEstudioDTO[];
  listaCurso: CursoDTO[] = [];
  listaSemestre: Isemestre[] = [];

  previaOcultar: boolean = false;
  idCursoPrevia: number;

  public formulario: FormGroup;
  public formularioSede: FormGroup;
  public formularioArea: FormGroup;
  public formularioCurso: FormGroup;

  constructor(private _snackBar: MatSnackBar, private router:Router, public dialog: MatDialog, protected carreraServ: CarreraService, protected sedeServ: SedesService, protected cursoServ: CursoService,
    protected areaServ: AreaEstudioService) { }

  ngOnInit(): void {

    this.areaServ.getAll().subscribe(
      (datos) => {
        this.listaAreasEstudio = datos;
      },(erro)=>{
        this.openSnackBar("No se puediero traer las areas de estudio de la base de dato")
      }
    );

    this.sedeServ.getAll().subscribe(
      (datos) => {
        this.listaSedes = datos;
      },(error)=>{
        this.openSnackBar("No se pudieron traer las sedes desde la base de dato");
      }
    );

    this.cursoServ.getAll().subscribe(
      (datos) => {
        this.listaCurso = datos;
      },(error)=>{
        this.openSnackBar("No se pudieron traer los cursos desde la base de dato");
      }
    );

    this.formulario = new FormGroup({
      nombre: new FormControl('', [Validators.required]),
      descripcion: new FormControl('', [Validators.required]),
    });

    this.formularioArea = new FormGroup({
      area: new FormControl('', [Validators.required]),
      creditos: new FormControl('', [Validators.required]),
    });

    this.formularioSede = new FormGroup({
      sede: new FormControl('', [Validators.required]),
    });

    this.formularioCurso = new FormGroup({
      curso: new FormControl('', [Validators.required]),
      optativo: new FormControl('', [Validators.required]),
    });
  }

  asignarSedes() {
    if (this.listaSedeSeleccionada.includes(this.formularioSede.controls['sede'].value)) {
      return;
    }

    this.listaSedeSeleccionada.push(this.formularioSede.controls['sede'].value);
    this.formularioSede.controls['sede'].setValue(undefined);
  }

  asignarArea() {
    if (this.listaAreaSeleccionada.includes(this.formularioArea.controls['area'].value)) {
      return;
    }
    let creditos: number = this.formularioArea.controls['creditos'].value

    this.formularioArea.controls['area'].value.creditos = creditos;
    this.listaAreaSeleccionada.push(this.formularioArea.controls['area'].value);

    this.formularioArea.controls['area'].setValue(undefined);
    this.formularioArea.controls['creditos'].setValue("");

  }

  asignarCurso() {
    if (this.listaTodosCursoSeleccionados.includes(this.formularioCurso.controls['curso'].value)) {
      return;
    }
    (this.formularioCurso.controls['curso'].value).semestre = this.contadorSemestres;

    let optativo:boolean = this.formularioCurso.controls['optativo'].value;
    (this.formularioCurso.controls['curso'].value).optativo = optativo;
    
    console.log(this.formularioCurso.controls['curso'].value);
    
    this.listaTodosCursoSeleccionados.push(this.formularioCurso.controls['curso'].value);
    this.listaCursoSeleccionados.push(this.formularioCurso.controls['curso'].value);
    this.formularioCurso.controls['curso'].setValue(undefined);
  }

  crearSemestre() {
    this.listaSemestre.push({
      clave: this.contadorSemestres,
      cursos: this.listaCursoSeleccionados
    });

    this.listaCursoSeleccionados.forEach(element => {
      this.listaCurso.splice(this.listaCurso.indexOf(element), 1);
    });

    this.contadorSemestres++;
    this.listaCursoSeleccionados = [];
    this.formularioCurso.controls['curso'].setValue(undefined);
  }

  cargarListaPrevia(curso: CursoDTO, claveSemestre: number) {
    this.listaCursoPrevias = [];
    this.idCursoPrevia = curso.id;
    let listaPrevia = [];
    this.listaSemestre.forEach(element => {
      if (element.clave < claveSemestre) {
        element.cursos.forEach(cursos => {
          this.listaCursoPrevias.push(cursos);
        });
      }
    });
    if (claveSemestre != 1) {
      const dialogRef = this.dialog.open(ModalPreviaComponent, {
        data: {
          curso: curso,
          listaCursos: this.listaCursoPrevias,
          listaPrevia: listaPrevia
        }
      });

      dialogRef.afterClosed().subscribe(result => {
        result.listaPrevia.forEach(element => {
          this.listaPrevias.push(element);
        });
      });

    } else {
      this.openSnackBar("No se puede agregar previas a las materias de primer semestre");
    }


  }

  validarForm():boolean{
    return this.formulario.valid && this.listaSemestre.length != 0 && this.listaSedes.length != 0  &&  this.listaAreaSeleccionada.length != 0
  }

  crear() {

    let carrera: CarreraCreateDTO = new CarreraCreateDTO();
    carrera.nombre = this.formulario.controls['nombre'].value;
    carrera.descripcion = this.formulario.controls['descripcion'].value;
    carrera.cant_semestres = this.contadorSemestres - 1

    carrera.sedes = this.listaSedeSeleccionada;
    carrera.areas_estudio = this.listaAreaSeleccionada;
    carrera.cursos = this.listaTodosCursoSeleccionados;
    carrera.previas = this.listaPrevias;
    console.log(carrera)

    this.carreraServ.create(carrera).subscribe(
      (datos) => {
        this.router.navigate(['/admin/carrera']);
      },
      (error) => {
        this.openSnackBar("No se pudo crear la carrera");
      }
    );
  }
  
  openSnackBar(mensaje : string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
