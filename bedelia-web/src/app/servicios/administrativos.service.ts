import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { SedeDTO } from '../clases/sede-dto';

@Injectable({
  providedIn: 'root'
})
export class AdministrativosService {

  private apiURL: string = environment.apiURL + '/administrativos';

  constructor(protected http: HttpClient) { }

  get(id: string) {
    return this.http.get<SedeDTO>(this.apiURL + "/" + id + "/sede");
  }

  asignar(datos:SedeDTO, id: string) {
    return this.http.post<SedeDTO>(this.apiURL + "/" + id +"/sede/", datos);
  }
}
