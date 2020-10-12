import { Component, OnInit } from '@angular/core';
import { MatTableDataSource } from '@angular/material/table';
import { CursoService } from 'src/app/servicios/curso.service';

@Component({
  selector: 'app-curso',
  templateUrl: './curso.component.html',
  styleUrls: ['./curso.component.css']
})
export class CursoComponent implements OnInit {
// columnas que se mostraran en la tabla
columnasAMostrar:string[] = ['nombre', 'descripcion', 'area_estudio', 'tipo_curso', 'accion'];
// objeto que necesita la tabla para mostrar el contenido
cursoDataSource = new MatTableDataSource([]);

constructor(protected cursoServ:CursoService) { }

ngOnInit(): void {
  // obtiene todos los cursos y los carga en el DataSource de la tala
  this.cursoServ.getAll().subscribe(
    (datos) => {
      this.cursoDataSource.data = datos;
    }
  );
}
}
