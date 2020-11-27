import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { ActaDTO } from 'src/app/clases/acta-dto';
import { ExamenDTO } from 'src/app/clases/examen-dto';
import { openSnackBar } from 'src/app/global-functions';
import { AnioLectivoService } from 'src/app/servicios/anio-lectivo.service';
import { ExamenesService } from 'src/app/servicios/examenes.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';
import { IngresarNotaExamenComponent } from './ingresar-nota-examen/ingresar-nota-examen.component';

@Component({
  selector: 'app-ingresar-resultado-examen',
  templateUrl: './ingresar-resultado-examen.component.html',
  styleUrls: ['./ingresar-resultado-examen.component.css']
})
export class IngresarResultadoExamenComponent implements OnInit {
  listaExamen: ExamenDTO[] = [];
  examenSeleccionado:boolean = false;
  acta:ActaDTO;
  notas:number[]=[];
  periodoOk: boolean = undefined;
  
  public formulario: FormGroup;
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['cedula', 'nombre', 'apellido', 'nota' ,'accion'];
  // objeto que necesita la tabla para mostrar el contenido
  usuariosDataSource = new MatTableDataSource([]);

  constructor(private router:Router, public dialog: MatDialog, private _snackBar: MatSnackBar,
    protected examenServ: ExamenesService, protected usuServ: UsuariosService,
    protected alecServ:AnioLectivoService) { }

  ngOnInit(): void {
    this.alecServ.enPeriodo('EX').subscribe(
      (data) => { this.periodoOk = true; },
      (error) => { this.periodoOk = false; }
    );
    this.examenServ.getExamenesDocente(this.usuServ.obtenerDatosLoginAlmacenado().cedula).subscribe(
      (datos) => {
        this.listaExamen = datos;
      }, (error) => {
        openSnackBar(this._snackBar, "Error al cargar los cursos");
      }
    );
    
    this.formulario = new FormGroup({
      examen: new FormControl('', [Validators.required])
    });
  }

  buscar(){
    this.examenServ.getNotasDeEstudiante(this.formulario.controls['examen'].value).subscribe(
      (datos) => {
        this.examenSeleccionado = true;
        this.acta = datos;
        this.usuariosDataSource.data = datos.notas;
      },
      (error) => {
        openSnackBar(this._snackBar, "Error al cargar los cursos");
      }
    );
  }

  confirmar(){
    this.examenServ.registrarNotas(this.formulario.controls['examen'].value, this.acta).subscribe(
      (datos)=>{
        this.router.navigate(['/']);
      },
      (error)=>{
        openSnackBar(this._snackBar, "Error al registrar las notas");
      }
    );
  }

  ingresarNota(ciEstudiante : string){
    const dialogRef = this.dialog.open(IngresarNotaExamenComponent,{width: '500px'});
    dialogRef.afterClosed().subscribe(result => {
      if (result == undefined) return; // se dio 'Volver'
      
      if(result > 5 || result < 1){
        openSnackBar(this._snackBar, "La nota a ingresar debe estar entre 1.0 y 5.0");
        return
      }
      this.acta.notas.forEach(element => {
        if(element.ciEstudiante == ciEstudiante){
          element.nota = result;
        }
      });

      this.usuariosDataSource.data.forEach(element => {
        if(element.cedula == ciEstudiante){
          element.nota = result;
        }
      });
    });
  }

}