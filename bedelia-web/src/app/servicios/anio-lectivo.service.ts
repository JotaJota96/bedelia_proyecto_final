import { formatDate } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from 'src/environments/environment';
import { AnioLectivoDTO } from '../clases/anio-lectivo-dto';

@Injectable({
  providedIn: 'root'
})
export class AnioLectivoService {

  private apiURL: string = environment.apiURL + '/periodos';

  constructor(protected http:HttpClient) { }

  get(){
    return this.http.get<AnioLectivoDTO>(this.apiURL);
  }

  create(datos:AnioLectivoDTO){
    return this.http.post<AnioLectivoDTO>(this.apiURL, datos) ;
  }

  enPeriodo(tipo:string){
    return this.http.get<AnioLectivoDTO>(this.apiURL + "/actual/" + tipo);
  }

}
