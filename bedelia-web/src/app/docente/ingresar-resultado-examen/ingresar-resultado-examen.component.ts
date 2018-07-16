import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { ActaDTO } from 'src/app/clases/acta-dto';
import { ExamenDTO } from 'src/app/clases/examen-dto';
import { ExamenesService } from 'src/app/servicios/examenes.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';
import { IngresarNotaExamenComponent } from './ingresar-nota-examen/ingresar-nota-examen.component';

@Component({
  selector: 'app-ingresar-resultado-examen',
  templateUrl: './ingresar-resultado-examen.component.html',
  styleUrls: ['./ingresar-resultado-examen.component.css']
})
export class IngresarResultadoExamenComponent implements OnInit {
  listaExamen: ExamenDTO[] = [];
  mostrar: boolean = false;
  acta:ActaDTO;
  notas:number[]=[];
  ciEstudiante : string;
  
  public formulario: FormGroup;
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['cedula', 'nombre', 'apellido', 'nota' ,'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  usuariosDataSource = new MatTableDataSource([]);

  constructor(private router:Router, public dialog: MatDialog, private _snackBar: MatSnackBar,protected examenServ: ExamenesService, protected usuServ: UsuariosService) { }

  ngOnInit(): void {
    this.examenServ.getExamenesDocente(this.usuServ.obtenerDatosLoginAlmacenado().cedula).subscribe(
      (datos) => {
        this.listaExamen = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
    
    this.formulario = new FormGroup({
      examen: new FormControl('', [Validators.required])
    });
  }

  buscar(){
    this.examenServ.getNotasDeEstudiante(this.formulario.controls['examen'].value).subscribe(
      (datos) => {
        this.acta = datos;
        this.usuariosDataSource.data = datos.notas;
      },
      (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
  }

  confirmar(){
    this.examenServ.registrarNotas(this.formulario.controls['examen'].value, this.acta).subscribe(
      (datos)=>{
        this.router.navigate(['/']);
      },
      (error)=>{
        this.openSnackBar("Error al registrar las notas");
      }
    );
  }

  ingresarNota(ciEstudiante : string){
    const dialogRef = this.dialog.open(IngresarNotaExamenComponent,{width: '500px'});
    dialogRef.afterClosed().subscribe(result => {
      if(result > 5 || result < 1){
        this.openSnackBar("La nota a ingresar deve estar entre 1 y 5");
        return
      }
      this.acta.notas.forEach(element => {
        if(element.ciEstudiante == ciEstudiante){
          element.nota = result;
        }
      });

      this.usuariosDataSource.data.forEach(element => {
        if(element.cedula == ciEstudiante){
          element.nota = result;
        }
      });
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