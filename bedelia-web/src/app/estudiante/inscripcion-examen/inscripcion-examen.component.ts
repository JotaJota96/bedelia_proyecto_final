import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { CursoService } from 'src/app/servicios/curso.service';

@Component({
  selector: 'app-inscripcion-examen',
  templateUrl: './inscripcion-examen.component.html',
  styleUrls: ['./inscripcion-examen.component.css']
})
export class InscripcionExamenComponent implements OnInit {
  selectedOptions: CursoDTO[] = [];
  listaCurso: CursoDTO[] = [];

  constructor(private _snackBar: MatSnackBar, protected cursoServ: CursoService) { }

  ngOnInit(): void {
    this.cursoServ.getAll().subscribe(
      (datos) => {
        this.listaCurso = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
      }
    );
  }

  confirmar() {
    console.log(this.selectedOptions)
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
