import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
  HttpHeaders
} from '@angular/common/http';
import { Observable } from 'rxjs';
import { UsuariosService } from '../servicios/usuarios.service';
import { LoginResponseDTO } from '../clases/login-response-dto';

@Injectable()
export class InterceptorTokenInterceptor implements HttpInterceptor {

  constructor(private usuServis:UsuariosService) {}

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    let loginData:LoginResponseDTO = this.usuServis.obtenerDatosLoginAlmacenado();

    if (loginData != null){
      const headers = new HttpHeaders({
        'Authorization': 'Bearer ' + loginData.token
      });
      const copia = req.clone({
        headers
      });
      return next.handle(copia);
    }else{
      const copia = req.clone();
      return next.handle(copia);
    }
  }
}
