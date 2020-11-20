import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { TipoCursoDTO } from '../clases/tipo-curso-dto';

@Injectable({
  providedIn: 'root'
})
export class TipoCursoService {

 
  private apiURL: string = environment.apiURL + '/tiposCurso';

  constructor(protected http:HttpClient) { }

  getAll(){
    return this.http.get<TipoCursoDTO[]>(this.apiURL);
  }

  get(id:number){
    return this.http.get<TipoCursoDTO>(this.apiURL + '/' + id);
  }

  create(datos:TipoCursoDTO){
    return this.http.post<TipoCursoDTO>(this.apiURL, datos);
  }
}
