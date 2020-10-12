import { Component, OnInit } from '@angular/core';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { CarreraService } from 'src/app/servicios/carrera.service';

@Component({
  selector: 'app-lista-carreras',
  templateUrl: './lista-carreras.component.html',
  styleUrls: ['./lista-carreras.component.css']
})
export class ListaCarrerasComponent implements OnInit {
  listaCarrera : CarreraDTO[];

  constructor(protected carreraServ: CarreraService) { }

  ngOnInit(): void {
    // obtiene todos los carrera y los carga en el DataSource de la tala
    this.carreraServ.getAll().subscribe(
      (datos) => {
        this.listaCarrera = datos;
      },
      (error)=>{
        alert("error");
      }
    );
  }
}
