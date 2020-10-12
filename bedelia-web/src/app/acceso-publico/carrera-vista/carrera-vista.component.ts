import { Component, OnInit } from '@angular/core';
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
    }
  }
}
