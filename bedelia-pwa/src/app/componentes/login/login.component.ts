import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Router } from '@angular/router';
import { ConnectionService } from 'ng-connection-service';
import { LoginDTO } from 'src/app/clases/login-dto';
import { LoginResponseDTO } from 'src/app/clases/login-response-dto';
import { UsuariosService } from 'src/app/servis/usuarios.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  public formulario: FormGroup;

  public datosLogin:LoginResponseDTO;

  constructor(private _snackBar: MatSnackBar,
    protected accServ: UsuariosService, 
    private router:Router,
    private onlineService:ConnectionService) {
  }

  ngOnInit(): void {

    if(!navigator.onLine){
      this.router.navigate(['/desconectado']);
      return;
    }
    
    this.onlineService.monitor().subscribe(
      (conectado)=>{
        if(!conectado){
          this.router.navigate(['/desconectado']);
          return;
        }
      }
    );

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
    // extrae los datos del formulario
    let datosLogin = new LoginDTO();
    datosLogin.id = this.formulario.controls['usuario'].value;
    datosLogin.contrasenia = this.formulario.controls['contrasenia'].value;

    this.accServ.login(datosLogin).subscribe(
      (retorno)=>{
        // si login es correcto
        this.datosLogin = retorno;
        this.vaciarCampos();

        retorno.roles.forEach(element => {
          if(element == "estudiante"){
            this.accServ.almacenarDatosLogin(this.datosLogin,"estudiante");
            this.router.navigate(['/']);
            return;
          }
        });
        
      },
      (error)=>{
        this.openSnackBar("Los datos de inicio de sesión son incorrectos");
        this.vaciarCampos();
      }
    );
  }

  openSnackBar(mensaje : string) {
    this._snackBar.open(mensaje,'Salir', {
      duration: 3000,
      horizontalPosition: 'center',
      verticalPosition: "bottom",
    });
  }
}
