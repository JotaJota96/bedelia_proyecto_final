import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { openSnackBar } from 'src/app/global-functions';
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
        openSnackBar(this._snackBar, "Error al cargar las sedes");
      }
    );
  }

}
