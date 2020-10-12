import { AreaEstudioDTO } from './area-estudio-dto';
import { TipoCursoDTO } from './tipo-curso-dto';

export class CursoDTO {
    public id                   :number;
    public nombre               :string;
    public descripcion          :string;
    public max_inasistencias    :number;
    public cant_creditos        :number;
    public cant_clases          :number;
    public area_estudio         :AreaEstudioDTO;
    public tipo_curso           :TipoCursoDTO;    
}

/*
  "id": 0,
  "nombre": "string",
  "descripcion": "string",
  "max_inasistencias": 0,
  "cant_creditos": 0,
  "cant_clases": 0,
  "area_estudio": {
    "id": 0,
    "area": "string"
  },
  "tipo_curso": {
    "id": 0,
    "tipo": "string"
  }
*/