import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { CarreraDTO } from '../clases/carrera-dto';
import { CursoDTO } from '../clases/curso-dto';
import { PreviasDTO } from '../clases/previas-dto';

@Injectable({
  providedIn: 'root'
})
export class CarreraService {

  private apiURL: string = environment.apiURL + '/carreras';

  constructor(protected http:HttpClient) { }

  getAll(){
    return this.http.get<CarreraDTO[]>(this.apiURL);
  }

  get(id:number){
    return this.http.get<CarreraDTO>(this.apiURL + '/' + id);
  }

  create(datos:CarreraDTO){
    return this.http.post<CarreraDTO>(this.apiURL, datos);
  }

  getAllCurso(id:number){
    return this.http.get<CursoDTO[]>(this.apiURL + '/' + id + '/cursos');
  }
  
  getAllPrevias(id:number){
    return this.http.get<PreviasDTO[]>(this.apiURL + '/' + id + '/previas');
  }
}
