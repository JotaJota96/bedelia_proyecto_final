import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { LoginDTO } from 'src/app/clases/login-dto';
import { UsuariosService } from 'src/app/servicios/usuarios.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  public formulario: FormGroup;
  public mostrarErrorLogin:boolean = false;

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
  }

  vaciarCampos(){
    this.formulario.controls['usuario'].setValue("");
    this.formulario.controls['contrasenia'].setValue("");
  }

  login(){
    let usuario = this.formulario.controls['usuario'].value;
    let contrasenia = this.formulario.controls['contrasenia'].value;

    let datosLogin = new LoginDTO();
    datosLogin.id = usuario;
    datosLogin.contrasenia = contrasenia;

    this.usuServ.login(datosLogin).subscribe(
      (retorno)=>{
        this.vaciarCampos();
        this.mostrarErrorLogin = false;
        //hacer algo si login es correcto
        this.router.navigate(['/']);
      },
      (error)=>{
        //datos incorrectos
        this.vaciarCampos();
        this.mostrarErrorLogin = true;
      }
    );
  }
}
