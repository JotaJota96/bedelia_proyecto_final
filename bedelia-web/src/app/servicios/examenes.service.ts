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

  getEdicionesParaInscrivirse(ci:string,idCarrera:number){
    return this.http.get<ExamenDTO[]>(this.apiURL + "/" + ci + "/" + idCarrera);
  }

  inscripciones(idExamen: number, ciEstudiante: string) {
    return this.http.post(this.apiURL + "/" + idExamen + "/inscripciones/" + ciEstudiante, null);
  }
}
