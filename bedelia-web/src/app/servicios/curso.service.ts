import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { CursoDTO } from '../clases/curso-dto';

@Injectable({
  providedIn: 'root'
})
export class CursoService {
  
  private apiURL: string = environment.apiURL + '/cursos';

  constructor(protected http:HttpClient) { }

  getAll(){
    return this.http.get<CursoDTO[]>(this.apiURL);
  }

  get(id:number){
    return this.http.get<CursoDTO>(this.apiURL + '/' + id);
  }

  create(datos:CursoDTO){
    return this.http.post<CursoDTO>(this.apiURL, datos);
  }
}
