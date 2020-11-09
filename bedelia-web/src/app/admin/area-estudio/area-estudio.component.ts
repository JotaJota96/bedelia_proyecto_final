import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { AreaEstudioService } from 'src/app/servicios/area-estudio.service';
import { AreaEstudioABMComponent } from './area-estudio-abm/area-estudio-abm.component';

@Component({
  selector: 'app-area-estudio',
  templateUrl: './area-estudio.component.html',
  styleUrls: ['./area-estudio.component.css']
})
export class AreaEstudioComponent implements OnInit {
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['id', 'area'];
  // objeto que necesita la tabla para mostrar el contenido
  areaDataSource = new MatTableDataSource([]);

  constructor(protected areaServ: AreaEstudioService, public dialog: MatDialog, private _snackBar: MatSnackBar) { }

  ngOnInit(): void {
    // obtiene todas las areas y los carga en el DataSource de la tala
    this.areaServ.getAll().subscribe(
      (datos) => {
        this.areaDataSource.data = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar las areas de estudio de la base de dato");
      }
    );
  }

  cargarDatos() {
    this.areaServ.getAll().subscribe(
      (datos) => {
        this.areaDataSource.data = datos;
      },
      (error) => {
        this.openSnackBar("No se pudieron cargar los dato");
      }
    );
  }

  openDialog() {
    const dialogRef = this.dialog.open(AreaEstudioABMComponent, { width: '500px' });

    dialogRef.afterClosed().subscribe(result => {
      this.cargarDatos();
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
