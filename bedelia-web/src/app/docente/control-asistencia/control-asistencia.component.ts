import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { ClaseDictadaDTO } from 'src/app/clases/clase-dictada-dto';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { EdicionCursoDTO } from 'src/app/clases/edicion-curso-dto';
import { openSnackBar } from 'src/app/global-functions';
import { AnioLectivoService } from 'src/app/servicios/anio-lectivo.service';
import { CursoService } from 'src/app/servicios/curso.service';
import { EdicionesCursoService } from 'src/app/servicios/ediciones-curso.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-control-asistencia',
  templateUrl: './control-asistencia.component.html',
  styleUrls: ['./control-asistencia.component.css']
})
export class ControlAsistenciaComponent implements OnInit {
  listaCurso: EdicionCursoDTO[] = [];
  mostrar:boolean = false;
  claseDictada : ClaseDictadaDTO;
  cursoSeleccionado: CursoDTO;
  periodoOk:boolean = undefined;

  public formulario: FormGroup;
   // columnas que se mostraran en la tabla
   columnasAMostrar: string[] = ['cedula', 'nombre', 'apellido','cant_asistencias', 'calculo' ,'accion'];
   // objeto que necesita la tabla para mostrar el contenido
   usuariosDataSource = new MatTableDataSource([]);

  constructor(private router:Router, private _snackBar: MatSnackBar, 
    protected usuServ: UsuariosService,protected edicionServ: EdicionesCursoService,
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

  confirmar(){
    this.mostrar = true;
    this.listaCurso.forEach(element => {
      if(element.id == this.formulario.controls['curso'].value){
        this.cursoSeleccionado = element.curso;
      }
    });

    this.claseDictada = undefined;
    
    this.edicionServ.getEstudiantesCurso(this.formulario.controls['curso'].value).subscribe(
      (datos)=>{
        datos.lista.forEach(element => {
          element.asistencia = 0;
        });

        this.claseDictada = datos;
        this.usuariosDataSource.data = datos.lista;
      }
    );
  }

  confirmarPasajeLista(){
    this.edicionServ.crearClaseDicta(this.formulario.controls['curso'].value, this.claseDictada).subscribe(
      (datos)=>{
        this.router.navigate(['/']);
      },
      (error)=>{
        openSnackBar(this._snackBar, "Error al registrar las asistencias");
      }
    );
  }

}
