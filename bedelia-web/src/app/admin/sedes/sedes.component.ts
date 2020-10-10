import { Component, OnInit } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { SedeDTO } from 'src/app/clases/sede-dto';
import { SedesService } from 'src/app/servicios/sedes.service';

@Component({
  selector: 'app-sedes',
  templateUrl: './sedes.component.html',
  styleUrls: ['./sedes.component.css']
})
export class SedesComponent implements OnInit {
  // columnas que se mostraran en la tabla
  columnasAMostrar:string[] = ['id', 'telefono', 'departamento', 'ciudad', 'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  sedeDataSource = new MatTableDataSource([]);

  constructor(protected sedeServ:SedesService) { }

  ngOnInit(): void {
    // obtiene todos los usuarios y los carga en el DataSource de la tala
    this.sedeServ.getAll().subscribe(
      (datos) => {
        this.sedeDataSource.data = datos;
      }
    );
  }

  aplicarFiltro(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.sedeDataSource.filter = filterValue.trim().toLowerCase();
  }

}
