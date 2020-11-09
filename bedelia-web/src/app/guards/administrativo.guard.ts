import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';
import { Observable } from 'rxjs';
import { UsuariosService } from '../servicios/usuarios.service';

@Injectable({
  providedIn: 'root'
})
export class AdministrativoGuard implements CanActivate {
  constructor(protected usuServis : UsuariosService, private router: Router){}
  
  canActivate(){
    if(this.usuServis.isAdministrativo()){
      return true;
    }else{
      this.router.navigate(['/']);
    }
  }
  
}
