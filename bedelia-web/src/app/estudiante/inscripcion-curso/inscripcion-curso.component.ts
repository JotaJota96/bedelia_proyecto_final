import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { CursoService } from 'src/app/servicios/curso.service';
import { EdicionesCursoService } from 'src/app/servicios/ediciones-curso.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-inscripcion-curso',
  templateUrl: './inscripcion-curso.component.html',
  styleUrls: ['./inscripcion-curso.component.css']
})
export class InscripcionCursoComponent implements OnInit {
  selectedOptions: CursoDTO[] = [];
  listaCurso: CursoDTO[] = [];
  
  constructor(private _snackBar: MatSnackBar, protected usuServ: UsuariosService, 
    protected cursoServ: CursoService, protected edicionCursoServ: EdicionesCursoService) { }

  ngOnInit(): void {
    this.cursoServ.getAll().subscribe(
      (datos) => {
        this.listaCurso = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
  }

  confirmar(){
    console.log(this.selectedOptions)
    let usu = this.usuServ.obtenerDatosLoginAlmacenado();
    this.selectedOptions.forEach(element => {
      this.edicionCursoServ.inscripciones(element.id, usu.cedula).subscribe(
        (datos)=>{
  
        },
        (error)=>{
  
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
