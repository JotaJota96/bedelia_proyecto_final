import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { EdicionCursoDTO } from 'src/app/clases/edicion-curso-dto';
import { ExamenDTO } from 'src/app/clases/examen-dto';
import { PersonaDTO } from 'src/app/clases/persona-dto';
import { UsuarioDTO } from 'src/app/clases/usuario-dto';
import { AdministrativosService } from 'src/app/servicios/administrativos.service';
import { AnioLectivoService } from 'src/app/servicios/anio-lectivo.service';
import { EdicionesCursoService } from 'src/app/servicios/ediciones-curso.service';
import { ExamenesService } from 'src/app/servicios/examenes.service';
import { SedesService } from 'src/app/servicios/sedes.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-asignar-docente',
  templateUrl: './asignar-docente.component.html',
  styleUrls: ['./asignar-docente.component.css']
})
export class AsignarDocenteComponent implements OnInit {

  listaCurso: EdicionCursoDTO[] = [];
  listaExamen: ExamenDTO[] = [];
  persona: PersonaDTO = new PersonaDTO;
  mostrarDatos: boolean = false;
  idSede: number;
  ciLogeado: string;
  periodoLeOk:boolean = undefined;
  periodoExOk:boolean = undefined;
  sedeOk:boolean = undefined;
  
  public formularioBusqueda: FormGroup;
  public formularioAsignarACurso: FormGroup;
  public formularioAsignarAExamen: FormGroup;

  constructor(private router: Router, private _snackBar: MatSnackBar,
    protected edicionCurServ: EdicionesCursoService,
    protected examenServ: ExamenesService,
    protected administrativoServ: AdministrativosService,
    protected sedeServ: SedesService,
    protected usuServ: UsuariosService,
    protected alecServ:AnioLectivoService) { }

  ngOnInit(): void {
    this.ciLogeado = this.usuServ.obtenerDatosLoginAlmacenado().cedula;
   
    this.administrativoServ.get(this.ciLogeado).subscribe(
      (datosSede) => {
        this.sedeOk = datosSede.id != null;
        if (this.sedeOk) {
          this.setPeriodoLeOk();
          this.setPeriodoExOk();

          this.sedeServ.getCrsos(datosSede.id).subscribe(
            (datos) => {
              this.listaCurso = datos;
            },
            (error) => {
              this.openSnackBar("Error al traer los cursos de la base de dato");
            }
          )

          this.sedeServ.getExamen(datosSede.id).subscribe(
            (datos) => {
              this.listaExamen = datos;
            },
            (error) => {
              this.openSnackBar("Error al traer los examenes de la base de dato");
            }
          )
        }
      }
    );

    this.formularioBusqueda = new FormGroup({
      ci: new FormControl('', [Validators.required]),
    });
    this.formularioAsignarACurso = new FormGroup({
      curso: new FormControl('', [Validators.required]),
    })
    this.formularioAsignarAExamen = new FormGroup({
      examen: new FormControl('', [Validators.required]),
    })
  }

  buscar() {
    var ci:string = this.formularioBusqueda.controls['ci'].value

    this.usuServ.get(ci).subscribe(
      (datos)=>{
        datos.roles.forEach(element => {
          if(element == "docente"){
            this.mostrarDatos = true
            this.persona = datos.persona;
          }
        });
      },
      (error)=>{
        this.mostrarDatos = false;
        this.persona = null;
        this.openSnackBar("La CI que se ingreso no es de un docente");
      }
    );
    
  }

  asignarCurso() {
    this.edicionCurServ.asignar(this.formularioAsignarACurso.controls['curso'].value, this.persona.cedula).subscribe(
      (datos) => {
        this.openSnackBar("El docente fue asignado correctamente");
        this.formularioAsignarACurso.controls['curso'].setValue(undefined);
      },
      (error) => {
        this.openSnackBar("Error al asignar el docente");
        this.mostrarDatos = false;
      }
    )
  }

  asignarExamen() {
    this.examenServ.asignarDocente(this.formularioAsignarAExamen.controls['examen'].value, this.persona.cedula).subscribe(
      (datos) => {
        this.openSnackBar("El docente fue asignado correctamente");
        this.formularioAsignarAExamen.controls['examen'].setValue(undefined);
      },
      (error) => {
        this.openSnackBar("Error al asignar el docente");
        this.mostrarDatos = false;
      }
    )
  }

  setPeriodoLeOk(){
    this.alecServ.enPeriodo('LE').subscribe(
      (data) => {  this.periodoLeOk = true; },
      (error) => { this.periodoLeOk =  this.periodoLeOk == true ? true : false; }
    );
    this.alecServ.enPeriodo('IC').subscribe(
      (data) => {  this.periodoLeOk = true; },
      (error) => { this.periodoLeOk =  this.periodoLeOk == true ? true : false; }
    );
  }

  setPeriodoExOk(){
    this.alecServ.enPeriodo('EX').subscribe(
      (data) => {  this.periodoExOk = true; },
      (error) => { this.periodoExOk =  this.periodoExOk == true ? true : false; }
    );
    this.alecServ.enPeriodo('IE').subscribe(
      (data) => {  this.periodoExOk = true; },
      (error) => { this.periodoExOk =  this.periodoExOk == true ? true : false; }
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
