import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { CarreraDTO } from '../clases/carrera-dto';

@Injectable({
  providedIn: 'root'
})
export class CarreraService {

  private apiURL: string = environment.apiURL + '/carrera';

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
}
