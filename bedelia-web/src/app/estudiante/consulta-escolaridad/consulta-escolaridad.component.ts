import { Component, OnInit } from '@angular/core';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { CarreraService } from 'src/app/servicios/carrera.service';

@Component({
  selector: 'app-consulta-escolaridad',
  templateUrl: './consulta-escolaridad.component.html',
  styleUrls: ['./consulta-escolaridad.component.css']
})
export class ConsultaEscolaridadComponent implements OnInit {
  listaCarrera : CarreraDTO[] = [];
  
  constructor(protected carreraServ: CarreraService) { }

  ngOnInit(): void {
    this.carreraServ.getAll().subscribe(
      (datos)=>{
        this.listaCarrera = datos;
      }
    );
  }

  obtenerEscolaridad(){
    
  }

}
