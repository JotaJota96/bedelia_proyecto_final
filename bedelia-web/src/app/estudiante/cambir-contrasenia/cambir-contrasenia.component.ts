import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';

@Component({
  selector: 'app-cambir-contrasenia',
  templateUrl: './cambir-contrasenia.component.html',
  styleUrls: ['./cambir-contrasenia.component.css']
})
export class CambirContraseniaComponent implements OnInit {
  abilitas:boolean = true;
  constructor(private _snackBar: MatSnackBar) { }

  public formulario: FormGroup;
  ngOnInit(): void {
    
    this.formulario = new FormGroup({
      contrasenia: new FormControl('', [Validators.required]),
      confirmarContrasenia: new FormControl('', [Validators.required]),
    });
  }

  confirmar(){
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
