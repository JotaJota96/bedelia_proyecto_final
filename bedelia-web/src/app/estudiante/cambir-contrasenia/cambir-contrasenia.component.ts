import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-cambir-contrasenia',
  templateUrl: './cambir-contrasenia.component.html',
  styleUrls: ['./cambir-contrasenia.component.css']
})
export class CambirContraseniaComponent implements OnInit {
  abilitas: boolean = true;
  constructor(private _snackBar: MatSnackBar, protected usuService: UsuariosService, private router: Router) { }

  public formulario: FormGroup;
  ngOnInit(): void {

    this.formulario = new FormGroup({
      contrasenia:          new FormControl('', [Validators.required, Validators.minLength(4)]),
      confirmarContrasenia: new FormControl('', [Validators.required, Validators.minLength(4)]),
    });
  }

  confirmar() {
    var login = this.usuService.obtenerDatosLoginAlmacenado();
    
    this.usuService.passReset(login.cedula, this.formulario.controls['contrasenia'].value).subscribe(
      (datos) => {
        this.router.navigate(['/']);
      },
      (error) => {
        this.openSnackBar("No se pudo cambiar la contraseña");
      }
    );
  }

  formularioValido(){
    if ( ! this.formulario.valid) return false;

    if (this.formulario.controls['contrasenia'].value != this.formulario.controls['confirmarContrasenia'].value) {
      return false;
    }
    return true;
  }

  openSnackBar(mensaje: string) {
    this._snackBar.open(mensaje, 'Salir', {
      duration: 3000,
      horizontalPosition: 'end',
      verticalPosition: "bottom",
    });
  }
}
