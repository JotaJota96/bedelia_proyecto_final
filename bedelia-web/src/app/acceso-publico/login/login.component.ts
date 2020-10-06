import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { LoginDTO } from 'src/app/clases/login-dto';
import { LoginResponseDTO } from 'src/app/clases/login-response-dto';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  public titulo:string = "Iniciar sesión";
  public formulario: FormGroup;
  public formularioRol: FormGroup;

  public mostrarErrorLogin: boolean = false;
  public roles: String[];
  public datosLogin:LoginResponseDTO;

  constructor(
    protected usuServ:UsuariosService, 
    private router:Router,
    private _snackBar: MatSnackBar) {
  }

  ngOnInit(): void {
    this.formulario = new FormGroup({
      usuario:     new FormControl('', [Validators.required]),
      contrasenia: new FormControl('', [Validators.required]),
    });
    this.formularioRol = new FormGroup({
      rol:     new FormControl('', [Validators.required]),
    });
    this.datosLogin = undefined;
  }

  vaciarCampos(){
    this.formulario.controls['usuario'].setValue("");
    this.formulario.controls['contrasenia'].setValue("");
    this.formularioRol.controls['rol'].setValue("");
  }
  eleguirRol(){
    let rol:String = this.formularioRol.controls['rol'].value;
    this.usuServ.almacenarDatosLogin(this.datosLogin, rol);
    this.router.navigate(['/']);
  }

  login(){
    // extrae los datos del formulario
    let datosLogin = new LoginDTO();
    datosLogin.id = this.formulario.controls['usuario'].value;
    datosLogin.contrasenia = this.formulario.controls['contrasenia'].value;

    this.usuServ.login(datosLogin).subscribe(
      (retorno)=>{
        // si login es correcto
        this.datosLogin = retorno;
        this.roles = retorno.roles

        this.vaciarCampos();
        this.mostrarErrorLogin = false;
        this.titulo = "Seleccione un rol"

        // si tiene un solo rol, se lo selecciona automaticamente
        if (this.roles.length == 1){
          this.formularioRol.controls['rol'].setValue(this.roles[0]);
          this.eleguirRol();
        }
      },
      (error)=>{
        //datos incorrectos
        this.vaciarCampos();
        this.mostrarErrorLogin = true;
      }
    );
  }
}
