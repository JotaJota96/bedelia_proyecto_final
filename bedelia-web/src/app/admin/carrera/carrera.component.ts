import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { CarreraService } from 'src/app/servicios/carrera.service';

@Component({
  selector: 'app-carrera',
  templateUrl: './carrera.component.html',
  styleUrls: ['./carrera.component.css']
})
export class CarreraComponent implements OnInit {
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['id', 'nombre', 'cant_semestres', 'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  carreraDataSource = new MatTableDataSource([]);

  constructor(protected carreraServ: CarreraService, private _snackBar: MatSnackBar) { }

  ngOnInit(): void {
    // obtiene todos los cursos y los carga en el DataSource de la tala
    this.carreraServ.getAll().subscribe(
      (datos) => {
        this.carreraDataSource.data = datos;
      }, (error) => {
        this.openSnackBar("No se pudieron cargar las carreras desde la base de dato");
      }
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
