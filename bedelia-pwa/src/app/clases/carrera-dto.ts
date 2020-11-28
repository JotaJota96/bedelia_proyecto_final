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
