import { Component, OnInit } from '@angular/core';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { ActivatedRoute, Router } from '@angular/router';
import { Edge, Node, Layout, ClusterNode } from '@swimlane/ngx-graph';
import { timeout } from 'rxjs/operators';
import { AreaEstudioDTO } from 'src/app/clases/area-estudio-dto';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { PreviasDTO } from 'src/app/clases/previas-dto';
import { SedeDTO } from 'src/app/clases/sede-dto';
import { CarreraService } from 'src/app/servicios/carrera.service';


@Component({
  selector: 'app-carrera-vista',
  templateUrl: './carrera-vista.component.html',
  styleUrls: ['./carrera-vista.component.css']
})
export class CarreraVistaComponent implements OnInit {
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['nombre'];
  // objeto que necesita la tabla para mostrar el contenido
  cursoDataSource = new MatTableDataSource([]);

  carrera: CarreraDTO = new CarreraDTO;
  
  listaAreaEstudo: AreaEstudioDTO[];
  listaSedes: SedeDTO[];

  ListaPrevias: PreviasDTO[];
  ListaCursos: CursoDTO[];

  public nodes: Node[] = [];
  public links: Edge[] = [];
  public semestre: ClusterNode[] = [];

  constructor(protected carreraServ: CarreraService, private rutaActiva: ActivatedRoute, private _snackBar: MatSnackBar) { }

  ngOnInit(): void {
    
    let parametrosId: number = this.rutaActiva.snapshot.params.id;

    if (parametrosId != undefined) {
      this.carreraServ.get(parametrosId).subscribe(
        (datos) => {
          this.carrera = datos;
          this.listaAreaEstudo = datos.areas_estudio;
          this.listaSedes = datos.sedes;

          this.carreraServ.getAllCurso(parametrosId).subscribe(
            (datos) => {
              this.cursoDataSource.data = datos;
              this.ListaCursos = datos;
              this.CargarNodos();
              this.crearSemestre();
            }
          );
          this.carreraServ.getAllPrevias(parametrosId).subscribe(
            (datos) => {
              this.ListaPrevias = datos;
              this.CargarLinks();
            }
          );
        },
        (error) => {
          this.openSnackBar("No se pudo cargar los datos desde la Base de dato");
          this.carrera = null;
        }
      );
      
    }
  }

  CargarNodos(){
    this.ListaCursos.forEach(element => {
      this.nodes.push({
        id: element.id + "",
        label: element.nombre,
      });
    });
  }

  CargarLinks(){
    this.ListaPrevias.forEach(element => {
      this.links.push({
        id: "Link_" + element.id,
        source: "" + element.curso_id_previa,
        target: "" + element.curso_id,
        label: element.tipo
      });
    });
  }

  crearSemestre(){
    console.log(this.carrera.cant_semestres)
    for(let index = 1; index <= this.carrera.cant_semestres; index++){
      console.log("1")
      let cursosSemestre : string[] = [];
      this.ListaCursos.forEach(element => {
        if(element.semestre == index){
          cursosSemestre.push(element.id+"")
        }
      });

      this.semestre.push({
        id: "Semestre_" + index,
        label: "Semestre " + index,
        childNodeIds: cursosSemestre,
      });
    }

  }

  openSnackBar(mensaje : string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}