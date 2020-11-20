import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { filter } from 'rxjs/operators';
import { openSnackBar } from 'src/app/global-functions';
import { CursoService } from 'src/app/servicios/curso.service';

@Component({
  selector: 'app-curso',
  templateUrl: './curso.component.html',
  styleUrls: ['./curso.component.css']
})
export class CursoComponent implements OnInit {
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['nombre', 'area_estudio', 'tipo_curso', 'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  cursoDataSource = new MatTableDataSource([]);

  constructor(private _snackBar: MatSnackBar, protected cursoServ: CursoService) { }

  ngOnInit(): void {
    // obtiene todos los cursos y los carga en el DataSource de la tala
    this.cursoServ.getAll().subscribe(
      (datos) => {
        this.cursoDataSource.data = datos;
      }, (error) => {
        openSnackBar(this._snackBar, "No se pudo cargar los cursos");
      }
    );
  }

  aplicarFiltro(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.cursoDataSource.filter = filterValue.trim().toLowerCase();

  }

}
