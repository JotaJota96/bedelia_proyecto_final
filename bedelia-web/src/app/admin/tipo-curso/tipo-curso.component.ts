import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { MatTableDataSource } from '@angular/material/table';
import { TipoCursoService } from 'src/app/servicios/tipo-curso.service';
import { TipoCursoABMComponent } from './tipo-curso-abm/tipo-curso-abm.component';

@Component({
  selector: 'app-tipo-curso',
  templateUrl: './tipo-curso.component.html',
  styleUrls: ['./tipo-curso.component.css']
})
export class TipoCursoComponent implements OnInit {
// columnas que se mostraran en la tabla
columnasAMostrar:string[] = ['id', 'tipo'];
// objeto que necesita la tabla para mostrar el contenido
tipoDataSource = new MatTableDataSource([]);

constructor(protected tipoServ:TipoCursoService, public dialog: MatDialog) { }

  ngOnInit(): void {
    this.tipoServ.getAll().subscribe(
      (datos) => {
        this.tipoDataSource.data = datos;
      }
    );
  }
 cargarDatos(){
   this.tipoServ.getAll().subscribe(
     (datos) => {
       this.tipoDataSource.data = datos;
     }
   );
 }
 openDialog() {
   const dialogRef = this.dialog.open(TipoCursoABMComponent,{width: '500px'});
   
   dialogRef.afterClosed().subscribe(result => {
     this.cargarDatos();
     console.log(`Dialog result: ${result}`);
   });
 }
}
