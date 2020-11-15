import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { error } from 'protractor';
import { openSnackBar } from 'src/app/global-functions';
import { EstudianteService } from 'src/app/servicios/estudiante.service';

@Component({
  selector: 'app-verificacion-escolaridad',
  templateUrl: './verificacion-escolaridad.component.html',
  styleUrls: ['./verificacion-escolaridad.component.css']
})
export class VerificacionEscolaridadComponent implements OnInit {

  public formulario: FormGroup;
  public mostrarSpinner = false;

  constructor(private router: Router, private _snackBar: MatSnackBar,
    protected estudianteServ : EstudianteService) { }

  ngOnInit(): void {
    this.formulario = new FormGroup({
      codigo: new FormControl('', [Validators.required]),
    });
  }

  verificar(){
    // this.estudianteServ.getEscolaridadPDFExiste(this.formulario.controls['codigo'].value).subscribe(
    //   (datos)=>{
    //     this.estudianteServ.getEscolaridadPDFCodigo(this.formulario.controls['codigo'].value);
    //   },
    //   (error)=>{
    //     openSnackBar(this._snackBar, "El codigo de verificacion no es valido");
    //   }
    // );
    this.mostrarSpinner = true;
    let codigo = this.formulario.controls['codigo'].value;

    this.estudianteServ.getEscolaridadPDFExiste(this.formulario.controls['codigo'].value).subscribe(
      (datos)=>{
        this.estudianteServ.getEscolaridadPDFCodigo(codigo).subscribe(
          (res) => {
            let file = new Blob([res], { type: 'application/pdf' });
            var fileURL = URL.createObjectURL(file);
            this.mostrarSpinner = false;
            window.location.assign(fileURL);
          }
        );
      },
      (error)=>{
        this.mostrarSpinner = false;
        openSnackBar(this._snackBar, "El codigo de verificacion no es valido");
      }
    );
  }

}
