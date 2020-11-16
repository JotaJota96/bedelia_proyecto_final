import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { openSnackBar } from 'src/app/global-functions';
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
        openSnackBar(this._snackBar, "No se pudo cargar las áreas de estudio");
      }
    );
  }

  cargarDatos() {
    this.areaServ.getAll().subscribe(
      (datos) => {
        this.areaDataSource.data = datos;
      },
      (error) => {
        openSnackBar(this._snackBar, "No se pudieron cargar los dato");
      }
    );
  }

  openDialog() {
    const dialogRef = this.dialog.open(AreaEstudioABMComponent, { width: '500px' });

    dialogRef.afterClosed().subscribe(result => {
      this.cargarDatos();
    });
  }

}
