import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { ActaDTO } from 'src/app/clases/acta-dto';
import { EdicionCursoDTO } from 'src/app/clases/edicion-curso-dto';
import { openSnackBar } from 'src/app/global-functions';
import { AdministrativosService } from 'src/app/servicios/administrativos.service';
import { EdicionesCursoService } from 'src/app/servicios/ediciones-curso.service';
import { ExamenesService } from 'src/app/servicios/examenes.service';
import { SedesService } from 'src/app/servicios/sedes.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-revicion-acta',
  templateUrl: './revicion-acta.component.html',
  styleUrls: ['./revicion-acta.component.css']
})
export class RevicionActaComponent implements OnInit {
  listaActas : ActaDTO[]=[];
  actaSeleccionada : ActaDTO = null;
  ciLogeado : string;
  sedeOk:boolean = undefined;

  public formulario: FormGroup;
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['cedula', 'nombre', 'apellido', 'nota'];
  // objeto que necesita la tabla para mostrar el contenido
  notasDataSource = new MatTableDataSource([]);


  constructor(protected administrativoServ: AdministrativosService, private router:Router, public dialog: MatDialog, private _snackBar: MatSnackBar,
    protected usuServ: UsuariosService, protected sedeServ: SedesService, protected examenServ: ExamenesService, protected edicionServ: EdicionesCursoService) { }

  ngOnInit(): void {
    this.ciLogeado = this.usuServ.obtenerDatosLoginAlmacenado().cedula;
    this.administrativoServ.get(this.ciLogeado).subscribe(
      (datosSede) => {
        this.sedeOk = datosSede.id != null;
        if (this.sedeOk) {
          this.sedeServ.getActas(datosSede.id).subscribe(
            (datos) => {
              this.listaActas = datos;
            }, (error) => {
            }
          );
        }
      },
      (error) => {
        openSnackBar(this._snackBar, "Error al cargar datos");
      }
    );

    this.formulario = new FormGroup({
      ActaCurso: new FormControl('', [Validators.required])
    });
  }

  buscar(){
    var acta:ActaDTO = this.formulario.controls['ActaCurso'].value
    if(acta.tipo == "LE"){
      this.edicionServ.getEdicionesParaActa(acta.id).subscribe(
        (datos)=>{
          this.actaSeleccionada = datos;
          this.notasDataSource.data = this.actaSeleccionada.notas;
        },
        (error)=>{
          
        }
      );
    }
    if(acta.tipo == "EX"){
      this.examenServ.getNotasDeEstudiante(acta.id).subscribe(
        (datos)=>{
          this.actaSeleccionada = datos;
          this.notasDataSource.data = this.actaSeleccionada.notas;
        },
        (error)=>{
          
        }
      );
    }
    
  }

  confirmar(){
    if(this.actaSeleccionada.acta_confirmada == true){
      openSnackBar(this._snackBar, "El acta ya esta confirmada");
    }
    if(this.actaSeleccionada.tipo == "LE"){
      //CURSO
      this.edicionServ.confirmarActa(this.actaSeleccionada.id).subscribe(
        (datos)=>{
          openSnackBar(this._snackBar, "El acta fue confirmada", 'ok');
          this.router.navigate(['/']);
        },
        (error)=>{
          openSnackBar(this._snackBar, "Error al confirmar el acta");
        });
    }
    if(this.actaSeleccionada.tipo == "EX"){
      //EXAMEN
      this.examenServ.confirmarActa(this.actaSeleccionada.id).subscribe(
        (datos)=>{
          openSnackBar(this._snackBar, "El acta fue confirmada", 'ok');
          this.router.navigate(['/']);
        },
        (error)=>{
          openSnackBar(this._snackBar, "Error al confirmar el acta");
        });
    }

  }

}
