import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { CursoService } from 'src/app/servicios/curso.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';
import { IngresarNotaExamenComponent } from './ingresar-nota-examen/ingresar-nota-examen.component';

@Component({
  selector: 'app-ingresar-resultado-examen',
  templateUrl: './ingresar-resultado-examen.component.html',
  styleUrls: ['./ingresar-resultado-examen.component.css']
})
export class IngresarResultadoExamenComponent implements OnInit {
  listaCurso: CursoDTO[] = [];
  mostrar: boolean = false;
  notas:number[]=[];
  
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['cedula', 'nombre', 'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  usuariosDataSource = new MatTableDataSource([]);

  constructor(public dialog: MatDialog, private _snackBar: MatSnackBar, protected usuServ: UsuariosService, protected cursoServ: CursoService) { }

  ngOnInit(): void {
    this.cursoServ.getAll().subscribe(
      (datos) => {
        this.listaCurso = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
  }

  buscar(id:number){
    this.mostrar = true;
    this.usuServ.getAll().subscribe(
      (datos) => {
        this.usuariosDataSource.data = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
  }

  confirmar(){

  }

  ingresarNota(){
    const dialogRef = this.dialog.open(IngresarNotaExamenComponent,{width: '500px'});
    dialogRef.afterClosed().subscribe(result => {
      //Se tiene que crear el dto para nota y agregarlo en el array
      this.notas.push(result);
      console.log(this.notas);
    });
  }

  aceptar(){
    
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}