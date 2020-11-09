import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { CarreraService } from 'src/app/servicios/carrera.service';

@Component({
  selector: 'app-lista-carreras',
  templateUrl: './lista-carreras.component.html',
  styleUrls: ['./lista-carreras.component.css']
})
export class ListaCarrerasComponent implements OnInit {
  listaCarrera : CarreraDTO[];

  constructor(protected carreraServ: CarreraService , private _snackBar: MatSnackBar) { }

  ngOnInit(): void {
    // obtiene todos los carrera y los carga en el DataSource de la tala
    this.carreraServ.getAll().subscribe(
      (datos) => {
        this.listaCarrera = datos;
      },
      (error)=>{
        this.openSnackBar("No se pudieron las carreras desde la Base de dato");
      }
    );
  }
  
  openSnackBar(mensaje : string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
