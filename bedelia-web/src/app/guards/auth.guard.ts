import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { UsuariosService } from '../servicios/usuarios.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {
  constructor(protected usuServis : UsuariosService, private router: Router){}
  
  canActivate(){
    if(this.usuServis.isLogged()){
      return true;
    }else{
      this.router.navigate(['/login']);
    }
  }
}
