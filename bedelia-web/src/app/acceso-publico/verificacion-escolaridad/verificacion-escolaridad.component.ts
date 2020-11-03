import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { error } from 'protractor';
import { EstudianteService } from 'src/app/servicios/estudiante.service';

@Component({
  selector: 'app-verificacion-escolaridad',
  templateUrl: './verificacion-escolaridad.component.html',
  styleUrls: ['./verificacion-escolaridad.component.css']
})
export class VerificacionEscolaridadComponent implements OnInit {

  public formulario: FormGroup;
  constructor(private router: Router, private _snackBar: MatSnackBar,
    protected estudianteServ : EstudianteService) { }

  ngOnInit(): void {
    this.formulario = new FormGroup({
      codigo: new FormControl('', [Validators.required]),
    });
  }

  verificar(){
    
    this.estudianteServ.getEscolaridadPDFExiste(this.formulario.controls['codigo'].value).subscribe(
      (datos)=>{
        this.estudianteServ.getEscolaridadPDFCodigo(this.formulario.controls['codigo'].value);
      },
      (error)=>{
        this.openSnackBar("El codigo de verificacion no es valido");
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
