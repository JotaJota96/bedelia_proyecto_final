import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { CarreraDTO } from '../clases/carrera-dto';
import { EscolaridadDTO } from '../clases/escolaridad-dto';

@Injectable({
  providedIn: 'root'
})
export class EstudianteService {
  private apiURL: string = environment.apiURL + '/estudiantes';

  constructor(protected http:HttpClient) { }

  getCarreras(ci:string){
    return this.http.get<CarreraDTO[]>(this.apiURL + "/"+ ci+"/carreras");
  }

  getEscolaridad(ci:string, idCarrera: number){
    return this.http.get<EscolaridadDTO>(this.apiURL + "/"+ ci+"/escolaridad/"+idCarrera);
  }
}
