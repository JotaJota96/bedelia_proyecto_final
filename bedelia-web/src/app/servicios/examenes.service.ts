import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ExamenesService {

  private apiURL: string = environment.apiURL + '/examenes';

  constructor(protected http: HttpClient) { }

  inscripciones(idExamen: number, ciEstudiante: string) {
    return this.http.post(this.apiURL + "/" + idExamen + "/inscripciones/" + ciEstudiante, null);
  }
}
