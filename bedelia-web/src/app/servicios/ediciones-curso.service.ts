import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { EdicionCursoDTO } from '../clases/edicion-curso-dto';
import { PersonaDTO } from '../clases/persona-dto';

@Injectable({
  providedIn: 'root'
})
export class EdicionesCursoService {

  private apiURL: string = environment.apiURL + '/edicionesCurso';

  constructor(protected http: HttpClient) { }

  inscripciones(idCurso: number, ciEstudiante: string) {
    return this.http.post(this.apiURL + "/" + idCurso + "/inscripciones/" + ciEstudiante, null);
  }

  asignar(id: number, ci: string) {
    return this.http.put<EdicionCursoDTO[]>(this.apiURL + "/" + id + "/docente/" + ci, null);
  }

  getEdicionesDocentes(ci:string){
    return this.http.get<EdicionCursoDTO[]>(this.apiURL + "/docente/" + ci);
  }
}
