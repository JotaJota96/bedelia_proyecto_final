import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { ActaDTO } from 'src/app/clases/acta-dto';
import { EdicionCursoDTO } from 'src/app/clases/edicion-curso-dto';
import { openSnackBar } from 'src/app/global-functions';
import { AnioLectivoService } from 'src/app/servicios/anio-lectivo.service';
import { EdicionesCursoService } from 'src/app/servicios/ediciones-curso.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';
import { IngresarNotaComponent } from './ingresar-nota/ingresar-nota.component';

@Component({
  selector: 'app-ingresar-resultado-curso',
  templateUrl: './ingresar-resultado-curso.component.html',
  styleUrls: ['./ingresar-resultado-curso.component.css']
})
export class IngresarResultadoCursoComponent implements OnInit {
  listaCurso: EdicionCursoDTO[] = [];
  cursoSeleccionado:boolean = false;
  acta:ActaDTO;
  notas:number[]=[];
  periodoOk:boolean = undefined;

  public formulario: FormGroup;
  // columnas que se mostraran en la tabla
  columnasAMostrar: string[] = ['cedula', 'nombre', 'apellido', 'nota','accion'];
  // objeto que necesita la tabla para mostrar el contenido
  usuariosDataSource = new MatTableDataSource([]);

  constructor(private router:Router, public dialog: MatDialog, private _snackBar: MatSnackBar, 
    protected usuServ: UsuariosService, protected edicionServ: EdicionesCursoService,
    protected alecServ:AnioLectivoService) { }

  ngOnInit(): void {
    this.alecServ.enPeriodo('LE').subscribe(
      (data) => { this.periodoOk = true; },
      (error) => { this.periodoOk = false; }
    );
    this.edicionServ.getEdicionesDocentes(this.usuServ.obtenerDatosLoginAlmacenado().cedula).subscribe(
      (datos) => {
        this.listaCurso = datos;
      }, (error) => {
        openSnackBar(this._snackBar, "No se pudo cargar los cursos");
      }
    );
    
    this.formulario = new FormGroup({
      curso: new FormControl('', [Validators.required])
    });
  }

  buscar(){
    this.edicionServ.getEdicionesParaActa(this.formulario.controls['curso'].value).subscribe(
      (datos) => {
        this.cursoSeleccionado = true;
        this.acta = datos;
        this.usuariosDataSource.data = datos.notas;
      },
      (error) => {
        openSnackBar(this._snackBar, "No se pudo cargar los cursos");
      }
    );
  }

  confirmar(){
    this.edicionServ.registrarNotasActa(this.formulario.controls['curso'].value, this.acta).subscribe(
      (datos)=>{
        this.router.navigate(['/']);
      },
      (error)=>{
        openSnackBar(this._snackBar, "Error al registrar las notas");
      }
    );
  }

  ingresarNota(ciEstudiante : string){
    const dialogRef = this.dialog.open(IngresarNotaComponent,{width: '500px'});
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