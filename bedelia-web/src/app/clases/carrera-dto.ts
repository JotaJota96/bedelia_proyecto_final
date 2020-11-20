import { AreaEstudioDTO } from './area-estudio-dto';
import { SedeDTO } from './sede-dto';

export class CarreraDTO {
    public id:              number;
    public nombre:          string;
    public descripcion:     string;
    public cant_semestres:  number;
    public areas_estudio:   AreaEstudioDTO[];
    public sedes:           SedeDTO[];
}
/*
{
  "id": 0,
  "nombre": "string",
  "descripcion": "string",
  "cant_semestres": "string",
  "areas_estudio": [
    {
      "id": 0,
      "area": "string",
      "creditos": 0
    }
  ],
  "sedes": []
}
*/