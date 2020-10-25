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

  inscripciones(idCurso: number, ciEstudiante: string) {
    return this.http.post(this.apiURL + "/" + idCurso + "/inscripciones/" + ciEstudiante, null);
  }

  asignar(id: number, ci: string) {
    return this.http.put<EdicionCursoDTO[]>(this.apiURL + "/" + id + "/docente/" + ci, null);
  }

  getEdicionesDocentes(ci:string){
    return this.http.get<EdicionCursoDTO[]>(this.apiURL + "/docente/" + ci);
  }
//---
  getEstudiantesCurso(id:number){
    return this.http.get<ClaseDictadaDTO>(this.apiURL + "/" + id + "/estudiantes/" );
  }
  
  crearClaseDicta(idEdicionCurso: number, ciEstu:string, asis:number) {
    return this.http.post(this.apiURL + "/" + idEdicionCurso + "/clasesDictada/", {ciEstudiante: ciEstu, asistencia:asis});
  }

  getEdicionesParaInscrivirse(ci:string,idCarrera:number){
    return this.http.get<EdicionCursoDTO[]>(this.apiURL + "/" + ci + "/" + idCarrera);
  }
}
