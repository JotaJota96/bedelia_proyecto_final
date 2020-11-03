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
  
  public formularioBusqueda: FormGroup;
  public formularioAsignar: FormGroup;

  constructor(private router: Router, private _snackBar: MatSnackBar,
    protected edicionCurServ: EdicionesCursoService,
    protected examenServ: ExamenesService,
    protected administrativoServ: AdministrativosService,
    protected sedeServ: SedesService,
    protected usuServ: UsuariosService) { }

  ngOnInit(): void {
    this.ciLogeado = this.usuServ.obtenerDatosLoginAlmacenado().cedula;
    
   
    this.formularioBusqueda = new FormGroup({
      ci: new FormControl('', [Validators.required]),
    });
    
    this.formularioAsignar = new FormGroup({
      curso: new FormControl('', [Validators.required]),
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

        if(this.persona != null){

          this.administrativoServ.get(this.ciLogeado).subscribe(
            (datos) => {
              if (datos.id != null) {
                this.sedeServ.getCrsos(datos.id).subscribe(
                  (datos) => {
                    datos.forEach(element => {
                      if(element.docente == null){
                        this.listaCurso.push(element);
                      }
                    });
                  },
                  (error) => {
                    this.openSnackBar("Error al traer los cursos de la base de dato");
                  }
                )
      
                this.sedeServ.getExamen(datos.id).subscribe(
                  (datos) => {
                    datos.forEach(element => {
                      if(element.docente == null){
                        this.listaExamen.push(element);
                      }
                    });
                  },
                  (error) => {
                    this.openSnackBar("Error al traer los examenes de la base de dato");
                  }
                )
              }
            }
          );
      
        }
      },
      (error)=>{
        this.openSnackBar("La CI que se ingreso no es de un docente");
      }
    );
    
  }

  asignarCurso() {
    this.edicionCurServ.asignar(this.formularioAsignar.controls['curso'].value, this.persona.cedula).subscribe(
      (datos) => {
        this.openSnackBar("El docente fue asignado correctamente");
        this.formularioAsignar.controls['curso'].setValue(undefined);
      },
      (error) => {
        this.openSnackBar("Error al asignar el docente");
        this.mostrarDatos = false;
      }
    )
  }

  asignarExamen() {
    this.examenServ.asignarDocente(this.formularioAsignar.controls['examen'].value, this.persona.cedula).subscribe(
      (datos) => {
        this.openSnackBar("El docente fue asignado correctamente");
        this.formularioAsignar.controls['examen'].setValue(undefined);
      },
      (error) => {
        this.openSnackBar("Error al asignar el docente");
        this.mostrarDatos = false;
      }
    )
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
