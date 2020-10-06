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

}
