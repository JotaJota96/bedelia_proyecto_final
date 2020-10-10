import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { AreaEstudioDTO } from '../clases/area-estudio-dto';

@Injectable({
  providedIn: 'root'
})
export class AreaEstudioService {

  private apiURL: string = environment.apiURL + '/areasEstudio';

  constructor(protected http:HttpClient) { }

  getAll(){
    return this.http.get<AreaEstudioDTO[]>(this.apiURL);
  }

  get(id:number){
    return this.http.get<AreaEstudioDTO>(this.apiURL + '/' + id);
  }

  create(datos:AreaEstudioDTO){
    return this.http.post<AreaEstudioDTO>(this.apiURL, datos);
  }
}
