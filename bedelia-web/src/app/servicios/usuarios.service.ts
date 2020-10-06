import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { tap } from 'rxjs/operators';
import { environment } from 'src/environments/environment';
import { LoginDTO } from '../clases/login-dto';
import { LoginResponseDTO } from '../clases/login-response-dto';


@Injectable({
  providedIn: 'root'
})
export class UsuariosService {

  private apiURL: string = environment.apiURL + '/usuarios';
  private loginDataStoreKey:string = "loginData"; // clave para almacenar datos en local
  
  constructor(protected http:HttpClient) { }

  login(datos:LoginDTO){
    return this.http.post<LoginResponseDTO>(this.apiURL + '/login', datos).pipe(
      tap((data) => {
        localStorage.setItem(this.loginDataStoreKey, JSON.stringify(data));
      })
    );
  }

  logout(){
    localStorage.removeItem(this.loginDataStoreKey); 
    localStorage.removeItem("rolSeleccionado"); 
  }
  /**
   * Devuelve los datos del usuario guardado en localstorage, o NULL si no hay ninguno
   */
  private obtenerUsuarioAlmacenado():LoginResponseDTO{
    return JSON.parse(localStorage.getItem(this.loginDataStoreKey))
  }

  /**
   * Devuelve true si hay un usuario logueado actualmente
   */
  isLogged(){
    let loginData:LoginResponseDTO = this.obtenerUsuarioAlmacenado();
    if (loginData != null){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Devueve true si el rol del usuario logueado es Estudiante
   */
  isEstudiante():boolean{
    let rolSeleccionado = localStorage.getItem("rolSeleccionado")
    console.log(rolSeleccionado);
    console.log(rolSeleccionado == "estudiante");
    return rolSeleccionado == "estudiante";
  }

   /**
   * Devueve true si el rol del usuario logueado es Estudiante
   */
  isDocente():boolean{
    let rolSeleccionado:string = localStorage.getItem("rolSeleccionado")
    console.log(rolSeleccionado)
    console.log(rolSeleccionado == "docente");
    return rolSeleccionado == "docente";
  }

   /**
   * Devueve true si el rol del usuario logueado es Estudiante
   */
  isAdministrativo():boolean{
    let rolSeleccionado:string = localStorage.getItem("rolSeleccionado")
    console.log(rolSeleccionado)
    console.log(rolSeleccionado == "administrativo");
    return rolSeleccionado == "administrativo";
  }

   /**
   * Devueve true si el rol del usuario logueado es Estudiante
   */
  isAdmin():boolean{
    let rolSeleccionado:string = localStorage.getItem("rolSeleccionado")
    console.log(rolSeleccionado)
    console.log(rolSeleccionado == "administrativo");
    return rolSeleccionado == "admin";
  }

  
  
}
