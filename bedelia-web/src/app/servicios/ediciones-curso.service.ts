import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { ClaseDictadaDTO } from '../clases/clase-dictada-dto';
import { EdicionCursoDTO } from '../clases/edicion-curso-dto';
import { PersonaDTO } from '../clases/persona-dto';

@Injectable({
  providedIn: 'root'
})
export class EdicionesCursoService {

  private apiURL: string = environment.apiURL + '/edicionesCurso';

  constructor(protected http: HttpClient) { }

  inscripciones(ciEstudiante: string, edicionesCursos:number[]) {
    return this.http.post(this.apiURL + "/inscripciones/" + ciEstudiante, edicionesCursos);
  }

  asignar(id: number, ci: string) {
    return this.http.put(this.apiURL + "/" + id + "/docente/" + ci, null);
  }

  getEdicionesDocentes(ci:string){
    return this.http.get<EdicionCursoDTO[]>(this.apiURL + "/docente/" + ci);
  }
//---
  getEstudiantesCurso(id:number){
    return this.http.get<ClaseDictadaDTO>(this.apiURL + "/" + id + "/estudiantes/" );
  }
  
  crearClaseDicta(idEdicionCurso: number, clasDic: ClaseDictadaDTO) {
    return this.http.post<ClaseDictadaDTO>(this.apiURL + "/" + idEdicionCurso + "/clasesDictada/", clasDic);
  }

  getEdicionesParaInscrivirse(ci:string,idCarrera:number){
    return this.http.get<EdicionCursoDTO[]>(this.apiURL + "/" + ci + "/" + idCarrera);
  }
}
