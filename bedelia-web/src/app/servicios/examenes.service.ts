import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { ExamenDTO } from '../clases/examen-dto';

@Injectable({
  providedIn: 'root'
})
export class ExamenesService {

  private apiURL: string = environment.apiURL + '/examenes';

  constructor(protected http: HttpClient) { }

  inscripciones(ciEstudiante: string, examen:number[]) {
    return this.http.post(this.apiURL + "/inscripciones/" + ciEstudiante, examen);
  }

  getEdicionesParaInscrivirse(ci:string,idCarrera:number){
    return this.http.get<ExamenDTO[]>(this.apiURL + "/" + ci + "/" + idCarrera);
  }

}
