import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { SedesService } from 'src/app/servicios/sedes.service';

@Component({
  selector: 'app-sedes',
  templateUrl: './sedes.component.html',
  styleUrls: ['./sedes.component.css']
})
export class SedesComponent implements OnInit {
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['id', 'telefono', 'departamento', 'ciudad', 'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  sedeDataSource = new MatTableDataSource([]);

  constructor(private _snackBar: MatSnackBar, protected sedeServ: SedesService) { }

  ngOnInit(): void {
    // obtiene todos las sedes y los carga en el DataSource de la tala
    this.sedeServ.getAll().subscribe(
      (datos) => {
        this.sedeDataSource.data = datos;
      }, (error) => {
        this.openSnackBar("Error al cargar las sedes de la base de dato");
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
