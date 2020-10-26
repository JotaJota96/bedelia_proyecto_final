import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatTableDataSource } from '@angular/material/table';
import { Router } from '@angular/router';
import { ClaseDictadaDTO } from 'src/app/clases/clase-dictada-dto';
import { CursoDTO } from 'src/app/clases/curso-dto';
import { EdicionCursoDTO } from 'src/app/clases/edicion-curso-dto';
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

  public formulario: FormGroup;
   // columnas que se mostraran en la tabla
   columnasAMostrar: string[] = ['cedula', 'nombre', 'apellido','cant_asistencias', 'calculo' ,'accion'];
   // objeto que necesita la tabla para mostrar el contenido
   usuariosDataSource = new MatTableDataSource([]);

  constructor(private router:Router, private _snackBar: MatSnackBar, 
    protected usuServ: UsuariosService,protected edicionServ: EdicionesCursoService) { }

  ngOnInit(): void {
    this.edicionServ.getEdicionesDocentes(this.usuServ.obtenerDatosLoginAlmacenado().cedula).subscribe(
      (datos) => {
        this.listaCurso = datos;
      }, (error) => {
        this.openSnackBar("No se pudo cargar los cursos desde la base de dato");
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
        this.openSnackBar("Error al registrar las asistencias");
      }
    );
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
