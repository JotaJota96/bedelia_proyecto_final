import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { error } from 'protractor';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { EscolaridadDTO } from 'src/app/clases/escolaridad-dto';
import { EstudianteService } from 'src/app/servicios/estudiante.service';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-consulta-escolaridad',
  templateUrl: './consulta-escolaridad.component.html',
  styleUrls: ['./consulta-escolaridad.component.css']
})
export class ConsultaEscolaridadComponent implements OnInit {
  listaCarrera : CarreraDTO[] = [];
  escolaridad: EscolaridadDTO = null;
  ciLogeado: string;

  constructor(private router: Router, private _snackBar: MatSnackBar,
     protected estudianteServ: EstudianteService,protected usuServ: UsuariosService) { }

  
  public formulario: FormGroup;
  ngOnInit(): void {
    this.ciLogeado = this.usuServ.obtenerDatosLoginAlmacenado().cedula;
    this.estudianteServ.getCarreras(this.ciLogeado).subscribe(
      (datos)=>{
        this.listaCarrera = datos;
      }
    );

    this.formulario = new FormGroup({
      carrera: new FormControl('', [Validators.required]),
    });
  }

  obtenerEscolaridad(){
    this.estudianteServ.getEscolaridad(this.ciLogeado,this.formulario.controls['carrera'].value).subscribe(
      (datos)=>{
        this.escolaridad = datos;
      },
      (error)=>{

      }
    );
  }

  descargarEscolaridad(){
    this.estudianteServ.getEscolaridadPDF(this.ciLogeado, this.formulario.controls['carrera'].value);
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
