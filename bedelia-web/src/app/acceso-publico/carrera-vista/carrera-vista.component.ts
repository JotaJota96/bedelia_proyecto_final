import { Component, OnInit } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { ActivatedRoute, Router } from '@angular/router';
import { AreaEstudioDTO } from 'src/app/clases/area-estudio-dto';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { SedeDTO } from 'src/app/clases/sede-dto';
import { CarreraService } from 'src/app/servicios/carrera.service';

@Component({
  selector: 'app-carrera-vista',
  templateUrl: './carrera-vista.component.html',
  styleUrls: ['./carrera-vista.component.css']
})
export class CarreraVistaComponent implements OnInit {
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['nombre', 'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  cursoDataSource = new MatTableDataSource([]);

  carrera: CarreraDTO = new CarreraDTO;
  
  listaAreaEstudo: AreaEstudioDTO[];
  listaSedes: SedeDTO[];

  constructor(protected carreraServ: CarreraService, private rutaActiva: ActivatedRoute) { }

  ngOnInit(): void {
    let parametrosId: number = this.rutaActiva.snapshot.params.id;

    if (parametrosId != undefined) {
      this.carreraServ.get(parametrosId).subscribe(
        (datos) => {
          this.carrera = datos;
          this.listaAreaEstudo = datos.areas_estudio;
          this.listaSedes = datos.sedes;
          console.log(this.listaAreaEstudo);
        },
        (error) => {
          alert("Error");
          this.carrera = null;
        }
      );
      this.carreraServ.getAllCurso(parametrosId).subscribe(
        (datos) => {
          this.cursoDataSource.data = datos;
        }
      );
    }
  }
}
