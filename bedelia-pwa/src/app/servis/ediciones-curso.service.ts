import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { EdicionCursoDTO } from '../clases/edicion-curso-dto';

@Injectable({
  providedIn: 'root'
})
export class EdicionesCursoService {
  private apiURL: string = environment.apiURL + '/edicionesCurso';

  constructor(protected http: HttpClient) { }

  inscripciones(ciEstudiante: string, edicionesCursos:number[]) {
    return this.http.post(this.apiURL + "/inscripciones/" + ciEstudiante, edicionesCursos);
  }

  getEdicionesDocentes(ci:string){
    return this.http.get<EdicionCursoDTO[]>(this.apiURL + "/docente/" + ci);
  }
  
  getEdicionesParaInscrivirse(ci:string,idCarrera:number){
    return this.http.get<EdicionCursoDTO[]>(this.apiURL + "/" + ci + "/" + idCarrera);
  }
}
