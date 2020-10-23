import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { CursoService } from 'src/app/servicios/curso.service';
import { ExamenesService } from 'src/app/servicios/examenes.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-inscripcion-examen',
  templateUrl: './inscripcion-examen.component.html',
  styleUrls: ['./inscripcion-examen.component.css']
})
export class InscripcionExamenComponent implements OnInit {
  selectedOptions: CursoDTO[] = [];
  listaExamen: CursoDTO[] = [];
  
  constructor(private _snackBar: MatSnackBar, protected usuServ: UsuariosService, 
    protected cursoServ: CursoService, protected examenServ: ExamenesService) { }

  ngOnInit(): void {
    this.cursoServ.getAll().subscribe(
      (datos) => {
        this.listaExamen = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
  }

  confirmar(){
    console.log(this.selectedOptions)
    let usu = this.usuServ.obtenerDatosLoginAlmacenado();
    this.selectedOptions.forEach(element => {
      this.examenServ.inscripciones(element.id, usu.cedula).subscribe(
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
