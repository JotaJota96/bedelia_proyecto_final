import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { ActaDTO } from 'src/app/clases/acta-dto';
import { EdicionCursoDTO } from 'src/app/clases/edicion-curso-dto';
import { EdicionesCursoService } from 'src/app/servicios/ediciones-curso.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';
import { IngresarNotaComponent } from './ingresar-nota/ingresar-nota.component';

@Component({
  selector: 'app-ingresar-resultado-curso',
  templateUrl: './ingresar-resultado-curso.component.html',
  styleUrls: ['./ingresar-resultado-curso.component.css']
})
export class IngresarResultadoCursoComponent implements OnInit {
  listaCurso: EdicionCursoDTO[] = [];
  mostrar:boolean = false;
  notas:number[]=[];
  acta:ActaDTO = new ActaDTO;

  public formulario: FormGroup;

   // columnas que se mostraran en la tabla
   columnasAMostrar: string[] = ['cedula', 'nombre', 'apellido', 'nota','accion'];
   // objeto que necesita la tabla para mostrar el contenido
   usuariosDataSource = new MatTableDataSource([]);

  constructor(private router:Router, public dialog: MatDialog, private _snackBar: MatSnackBar, protected usuServ: UsuariosService, protected edicionServ: EdicionesCursoService) { }

  ngOnInit(): void {
    this.edicionServ.getEdicionesDocentes(this.usuServ.obtenerDatosLoginAlmacenado().cedula).subscribe(
      (datos) => {
        this.listaCurso = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
    
    this.formulario = new FormGroup({
      curso: new FormControl('', [Validators.required])
    });
  }

  buscar(){
    this.edicionServ.getEdicionesParaActa(this.formulario.controls['curso'].value).subscribe(
      (datos) => {
        this.acta = datos
        datos.notas.forEach(element => {
          element.nota = 0
        });
        this.usuariosDataSource.data = datos.notas;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
  }

  confirmar(){
    this.edicionServ.registrarNotasActa(this.formulario.controls['curso'].value, this.acta).subscribe(
      (datos)=>{
        this.router.navigate(['/']);
      },
      (error)=>{
        this.openSnackBar("Error al registrar las notas");
      }
    );
  }

  ingresarNota(ciEstudiante : string){
    const dialogRef = this.dialog.open(IngresarNotaComponent,{width: '500px'});
    dialogRef.afterClosed().subscribe(result => {
      //Se tiene que crear el dto para nota y agregarlo en el array
      var nota = result;
      console.log(this.notas);
      this.usuariosDataSource.data.forEach(element => {
        if(element.ci == ciEstudiante){
          element.nota = nota;
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