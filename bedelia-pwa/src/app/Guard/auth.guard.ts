import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { Observable } from 'rxjs';
import { UsuariosService } from '../servis/usuarios.service';

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
