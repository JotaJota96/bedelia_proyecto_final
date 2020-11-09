import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { LoginDTO } from 'src/app/clases/login-dto';
import { LoginResponseDTO } from 'src/app/clases/login-response-dto';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-cambir-contrasenia',
  templateUrl: './cambir-contrasenia.component.html',
  styleUrls: ['./cambir-contrasenia.component.css']
})
export class CambirContraseniaComponent implements OnInit {
  login: LoginResponseDTO;
  public formulario: FormGroup;

  constructor(private _snackBar: MatSnackBar, protected usuService: UsuariosService, private router: Router) { }

  ngOnInit(): void {
    this.login = this.usuService.obtenerDatosLoginAlmacenado();

    this.formulario = new FormGroup({
      contraseniaActual:    new FormControl('', [Validators.required, Validators.minLength(4)]),
      contrasenia:          new FormControl('', [Validators.required, Validators.minLength(4)]),
      confirmarContrasenia: new FormControl('', [Validators.required, Validators.minLength(4)]),
    });
  }

  confirmar() {
    this.usuService.passReset(this.login.cedula, this.formulario.controls['contrasenia'].value).subscribe(
      (datos) => {
        this.router.navigate(['/']);
      },
      (error) => {
        this.openSnackBar("No se pudo cambiar la contraseña");
      }
    );
  }

  formularioValido(){
    if ( !this.formulario.valid) return false;

    if (this.formulario.controls['contrasenia'].value != this.formulario.controls['confirmarContrasenia'].value) {
      return false;
    }
    return true;
  }

  validarContraseniaActual(){
    let ca:string = this.formulario.controls['contraseniaActual'].value;
    let ci:string = this.login.cedula;
    
    if (ca.length < 4)  {
      this.formulario.controls['contraseniaActual'].setErrors({'incorrecto': true});
      return;
    }


    this.usuService.passChk(ci, ca).subscribe(
      (datos) => {
        this.formulario.controls['contraseniaActual'].setErrors(null);
      },
      (error) => {
        this.formulario.controls['contraseniaActual'].setErrors({'incorrecto': true});
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
