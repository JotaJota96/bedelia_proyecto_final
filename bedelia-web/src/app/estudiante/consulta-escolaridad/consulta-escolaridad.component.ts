import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { error } from 'protractor';
import { CarreraDTO } from 'src/app/clases/carrera-dto';
import { EscolaridadDTO } from 'src/app/clases/escolaridad-dto';
import { openSnackBar } from 'src/app/global-functions';
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
  mostrarSpinner = false;

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
    //this.estudianteServ.getEscolaridadPDF(this.ciLogeado, this.formulario.controls['carrera'].value);
    
    this.mostrarSpinner = true;
    let idCarrera = this.formulario.controls['carrera'].value;
    this.estudianteServ.getEscolaridadPDF(this.ciLogeado, idCarrera).subscribe(
      (res) => {
        let file = new Blob([res], { type: 'application/pdf' });
        var fileURL = URL.createObjectURL(file);
        this.mostrarSpinner = false;
        window.location.assign(fileURL);
      },
      (error)=>{
        this.mostrarSpinner = false;
        openSnackBar(this._snackBar, "El codigo de verificacion no es valido");
      }
    );
  }

}
