import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { ActaDTO } from '../clases/acta-dto';
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

  asignarDocente(idExamen:number,ciDocente:string){
    return this.http.put(this.apiURL + "/" + idExamen + "/docente/" + ciDocente, null);
  }

  getEdicionesParaInscribirse(ci:string, idCarrera:number){
    return this.http.get<ExamenDTO[]>(this.apiURL + "/" + ci + "/" + idCarrera);
  }

  getExamenesDocente(ciDocente:string){
    return this.http.get<ExamenDTO[]>(this.apiURL + "/docente/" + ciDocente);
  }

  getNotasDeEstudiante(idExamen:number){
    return this.http.get<ActaDTO>(this.apiURL + "/" + idExamen + "/notas");
  }

  confirmarActa(idExamen:number){
    return this.http.put<ActaDTO  >(this.apiURL + "/" + idExamen + "/notas", null);
  }

  registrarNotas(idExamen:number, acta:ActaDTO) {
    return this.http.post(this.apiURL + "/" + idExamen + "/notas", acta);
  }
}
