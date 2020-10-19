import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { PostulanteDTO } from '../clases/postulante-dto';
import { SedeDTO } from '../clases/sede-dto';

@Injectable({
  providedIn: 'root'
})
export class SedesService {

  private apiURL: string = environment.apiURL + '/sedes';

  constructor(protected http:HttpClient) { }

  getAll(){
    return this.http.get<SedeDTO[]>(this.apiURL);
  }

  get(id:number){
    return this.http.get<SedeDTO>(this.apiURL + '/' + id);
  }

  create(datos:SedeDTO){
    return this.http.post<SedeDTO>(this.apiURL, datos);
  }

  getSedes(id:number){
    return this.http.get<PostulanteDTO[]>(this.apiURL + '/' + id + '/postulantes');
  }
}
